<?php

namespace App\Services;

use App\Models\InboxItem;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class InboxPromoter
{
    public function promoteToMaterial(InboxItem $item, array $overrides = []): Material
    {
        if ($item->status === 'promoted' && $item->promoted_to_material_id) {
            return Material::findOrFail($item->promoted_to_material_id);
        }

        return DB::transaction(function () use ($item, $overrides) {
            $material = Material::create([
                'course_id' => $item->course_id,
                'title' => $overrides['title'] ?? $item->title,
                'type' => $overrides['type'] ?? 'link',
                'url' => $overrides['url'] ?? $item->url,
                'note' => $overrides['note'] ?? $item->note,
                'source' => $item->source,
                'captured_at' => $item->captured_at,
                'inbox_item_id' => $item->id,
            ]);

            if ($item->tags->isNotEmpty()) {
                $material->syncTags($item->tags->pluck('name')->toArray());
            }

            if (isset($overrides['task_ids']) && is_array($overrides['task_ids'])) {
                $material->tasks()->attach($overrides['task_ids']);
            }

            $item->update([
                'status' => 'promoted',
                'promoted_to_material_id' => $material->id,
                'processed_at' => now(),
            ]);

            return $material;
        });
    }
}
