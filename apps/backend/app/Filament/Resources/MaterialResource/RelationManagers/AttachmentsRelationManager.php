<?php

namespace App\Filament\Resources\MaterialResource\RelationManagers;

use App\Models\Attachment;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Attachments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('storage_path')
                    ->label('Upload File')
                    ->disk('private')
                    ->directory('attachments/' . date('Y/m'))
                    ->preserveFilenames()
                    ->required()
                    ->maxSize(51200) // 50MB
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'])
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('label')
                    ->placeholder('Optional Label')
                    ->maxLength(255),

                Textarea::make('note')
                    ->placeholder('Optional Note')
                    ->rows(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_name')
            ->columns([
                TextColumn::make('original_name')
                    ->label('File Name')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->icon('heroicon-o-document'),

                TextColumn::make('size_bytes')
                    ->label('Size')
                    ->formatStateUsing(fn($record) => $record->human_readable_size),

                TextColumn::make('uploaded_at')
                    ->label('Uploaded')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('version_number')
                    ->label('Ver')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('is_current')
                    ->label('Current')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No'),

                TextColumn::make('is_final')
                    ->label('Final')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Final' : 'Draft'),
            ])
            ->defaultSort('uploaded_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('upload_r2')
                    ->label('Unggah ke Cloud')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->modalHeading('Unggah File ke R2')
                    ->modalContent(fn($livewire) => view('filament.components.r2-upload-modal', [
                        'attachable_type' => $livewire->ownerRecord instanceof \App\Models\Material ? 'material' : 'task',
                        'attachable_id' => $livewire->ownerRecord->id,
                        'modal_id' => 'upload_r2',
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->extraModalWindowAttributes(['x-on:close-modal.window' => 'if ($event.detail.id === "upload_r2") close()']),
            ])
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn(Attachment $record) => $record->getDownloadResponse()),

                Action::make('new_version')
                    ->label('New Ver')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->modalHeading('Upload New Version')
                    ->modalContent(fn($record, $livewire) => view('filament.components.r2-upload-modal', [
                        'attachable_type' => $livewire->ownerRecord instanceof \App\Models\Material ? 'material' : 'task',
                        'attachable_id' => $livewire->ownerRecord->id,
                        'attachment_group_id' => $record->attachment_group_id,
                        'modal_id' => 'new_ver_' . $record->id,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->extraModalWindowAttributes(['x-on:close-modal.window' => 'if ($event.detail.id.startsWith("new_ver_")) close()']),

                Action::make('toggle_final')
                    ->label(fn(Attachment $record) => $record->is_final ? 'Un-Final' : 'Set Final')
                    ->color(fn(Attachment $record) => $record->is_final ? 'warning' : 'success')
                    ->icon('heroicon-o-check-badge')
                    ->action(function (Attachment $record) {
                        if ($record->is_final) {
                            $record->update(['is_final' => false]);
                        } else {
                            // Unset others in group
                            Attachment::where('attachment_group_id', $record->attachment_group_id)
                                ->update(['is_final' => false]);
                            $record->update(['is_final' => true]);
                        }
                    })
                    ->requiresConfirmation(),

                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
