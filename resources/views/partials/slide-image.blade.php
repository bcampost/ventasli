@php
  $src = asset('storage/'.$slide->image_path);
@endphp

<div class="ref-media">
  <img src="{{ $src }}" alt="{{ $slide->title ?? 'slide' }}" class="ref-img" loading="lazy"/>
</div>

<style>
  .ref-media{
    height: 420px;
    background: #ffffff;
  }

  /* Igual a referencia: llena el card */
  .ref-img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
  }

  @media (max-width: 1100px){
    .ref-media{ height: 360px; }
  }
  @media (max-width: 640px){
    .ref-media{ height: 280px; }
  }
</style>