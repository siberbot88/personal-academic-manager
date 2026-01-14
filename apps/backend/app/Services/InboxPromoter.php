<?php

namespace App\Services;

use App\Models\InboxItem;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class InboxPromoter
{
    /**
     * Promote an inbox item to a material
     * 
     * @param InboxItem $item
     * @param array $overrides ['type' => 'note', 'title' => '...', etc]
     * @return Material
     */
    public function promoteToMaterial(InboxItem $item, array $overrides = []): Material
    {
        // Check if already promoted (idempotent)
        if ($item->status === 'promoted' && $item->promoted_to_material_id) {
            return Material::findOrFail($item->promoted_to_material_id);
        }

        return DB::transaction(function () use ($item, $overrides) {
            // Create material from inbox
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

            // Copy tags from inbox to material
            if ($item->tags->isNotEmpty()) {
                $material->syncTags($item->tags->pluck('name')->toArray());
            }

            // Link to task if provided
            if (isset($overrides['task_id'])) {
                $material->tasks()->attach($overrides['task_id']);
            }

            // Mark inbox as promoted
            $item->update([
                'status' => 'promoted',
                'promoted_to_material_id' => $material->id,
                'processed_at' => now(),
            ]);

            return $material;
        });
    }
}
