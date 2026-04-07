<?php

Schema::create('menu_product_material_color', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('menu_product_id');
    $table->unsignedBigInteger('material_color_id');
    $table->timestamps();
});