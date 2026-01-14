<?php

namespace App\Filament\Resources\InboxItems;

use App\Filament\Resources\InboxItems\Pages\CreateInboxItem;
use App\Filament\Resources\InboxItems\Pages\EditInboxItem;
use App\Filament\Resources\InboxItems\Pages\ListInboxItems;
use App\Filament\Resources\InboxItems\Schemas\InboxItemForm;
use App\Filament\Resources\InboxItems\Tables\InboxItemsTable;
use App\Models\InboxItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InboxItemResource extends Resource
{
    protected static ?string $model = InboxItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'Inbox';

    protected static string|\UnitEnum|null $navigationGroup = 'Materi';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return InboxItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InboxItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInboxItems::route('/'),
            'create' => CreateInboxItem::route('/create'),
            'edit' => EditInboxItem::route('/{record}/edit'),
        ];
    }
}
