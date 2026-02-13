<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $nodes = DB::table('menu_nodes')->select('id','parent_id','label','slug')->get();

        foreach ($nodes as $n) {
            if (!empty($n->slug)) continue;

            $parentId = (int)($n->parent_id ?? 0);
            $base = Str::slug($n->label ?? 'item', '-');
            if ($base === '') $base = 'item';

            $slug = $base;
            $i = 2;

            while (
                DB::table('menu_nodes')
                    ->where('parent_id', $parentId)
                    ->where('slug', $slug)
                    ->where('id', '!=', $n->id)
                    ->exists()
            ) {
                $slug = $base.'-'.$i;
                $i++;
            }

            DB::table('menu_nodes')->where('id', $n->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // No-op
    }
};