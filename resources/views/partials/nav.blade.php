@php
$menu = [
  [
    'label' => 'Listas de precios',
    'children' => [
      ['label' => 'Mobiliario', 'url' => '#'],
      ['label' => 'Sillería', 'url' => '#'],
      ['label' => 'Comercialización', 'url' => '#'],
    ],
  ],
  [
    'label' => 'Productos',
    'children' => [
      ['label' => 'Detalles de productos', 'url' => '#'],

      ['label' => 'Escritorios', 'children' => [
        ['label' => 'Anzio', 'url' => '#'],
        ['label' => 'Vetta', 'url' => '#'],
        ['label' => 'Aura', 'url' => '#'],
        ['label' => 'Vasari', 'url' => '#'],
        ['label' => 'Livello', 'url' => '#'],
        ['label' => 'Italia', 'url' => '#'],
        ['label' => 'E-link', 'url' => '#'],
        ['label' => 'Urban', 'url' => '#'],
      ]],

      ['label' => 'Estaciones de trabajo', 'children' => [
        ['label' => 'Setto', 'url' => '#'],
        ['label' => 'Anzio', 'url' => '#'],
        ['label' => 'Vetta', 'url' => '#'],
        ['label' => 'Aura', 'url' => '#'],
        ['label' => 'Vasari', 'url' => '#'],
        ['label' => 'Livello', 'url' => '#'],
        ['label' => 'iWork', 'url' => '#'],
        ['label' => 'E-link', 'url' => '#'],
      ]],

      ['label' => 'Sillería', 'children' => [
        ['label' => 'Ejecutivas', 'url' => '#'],
        ['label' => 'Operativas', 'url' => '#'],
        ['label' => 'Visita', 'url' => '#'],
        ['label' => 'Comedor', 'url' => '#'],
      ]],

      ['label' => 'Almacenamiento', 'children' => [
        ['label' => 'Anzio', 'url' => '#'],
        ['label' => 'Filio', 'url' => '#'],
        ['label' => 'Italia', 'url' => '#'],
        ['label' => 'Urban', 'url' => '#'],
        ['label' => 'iWork', 'url' => '#'],
        ['label' => 'Vasari', 'url' => '#'],
      ]],

      ['label' => 'Mesas', 'children' => [
        ['label' => 'Anzio', 'url' => '#'],
        ['label' => 'Aura', 'url' => '#'],
        ['label' => 'Italia', 'url' => '#'],
        ['label' => 'Vasari', 'url' => '#'],
      ]],

      ['label' => 'Salas', 'children' => [
        ['label' => 'Attesa', 'url' => '#'],
      ]],

      ['label' => 'Recepciones', 'children' => [
        ['label' => 'Anzio', 'url' => '#'],
        ['label' => 'Italia', 'url' => '#'],
      ]],

      ['label' => 'Cabinas', 'children' => [
        ['label' => 'Acusto', 'url' => '#'],
      ]],

      ['label' => 'Accesorios', 'children' => [
        ['label' => 'Generales', 'url' => '#'],
      ]],

      ['label' => 'Inventarios', 'children' => [
        ['label' => 'Mobiliario', 'url' => '#'],
        ['label' => 'Sillería', 'url' => '#'],
      ]],

      ['label' => 'Comercialización', 'children' => [
        ['label' => 'Master Offiho', 'url' => '#'],
        ['label' => 'Master Offichairs', 'url' => '#'],
        ['label' => 'Master Albar', 'url' => '#'],
        ['label' => 'Master Cabfun', 'url' => '#'],
        ['label' => 'Master muebles noriega', 'url' => '#'],
      ]],
    ],
  ],
  [
    'label' => 'Material Visual',
    'children' => [
      ['label' => 'Catálogos', 'children' => [
        ['label' => '2025', 'url' => '#'],
        ['label' => '2026', 'url' => '#'],
        ['label' => 'Acabados', 'url' => '#'],
        ['label' => 'Acusto', 'url' => '#'],
        ['label' => 'Acusto con precios', 'url' => '#'],
        ['label' => 'Prospección', 'url' => '#'],
        ['label' => 'Brochure Setto', 'url' => '#'],
        ['label' => 'Brochure Vetta', 'url' => '#'],
        ['label' => 'Brouche Aura', 'url' => '#'],
        ['label' => 'Folleto Ley Sillas', 'url' => '#'],
      ]],
      ['label' => 'Renders', 'url' => '#'],
      ['label' => 'Fotos', 'url' => '#'],
      ['label' => 'Videos', 'url' => '#'],
      ['label' => 'Proyectos anteriores', 'children' => [
        ['label' => 'Banner con video de caso de éxito', 'url' => '#'],
        ['label' => 'Casos de éxito', 'url' => '#'],
        ['label' => 'Referencias', 'url' => '#'],
      ]],
    ],
  ],
  [
    'label' => 'Herramientas de Venta',
    'children' => [
      ['label' => 'Tabuladores', 'url' => '#'],
      ['label' => 'Envíos', 'children' => [
        ['label' => 'Armados', 'url' => '#'],
      ]],
      ['label' => 'Documentos', 'children' => [
        ['label' => 'Semblanza 2025', 'url' => '#'],
        ['label' => 'Semblanza Inglés', 'url' => '#'],
        ['label' => 'Semblanza Intermediarios', 'url' => '#'],
        ['label' => 'Formato de rentabilidad', 'url' => '#'],
        ['label' => 'Póliza de Garantía', 'url' => '#'],
        ['label' => 'Recomendación de uso', 'url' => '#'],
      ]],
    ],
  ],
  [
    'label' => 'Documentación',
    'children' => [
      ['label' => 'Documentación fiscal y legal', 'children' => [
        ['label' => 'Editables para área fiscal y contabilidad', 'url' => '#'],
      ]],
    ],
  ],
];
@endphp

<header class="bg-white border-b sticky top-0 z-40">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
    <a href="{{ route('home') }}" class="font-semibold">ventasli</a>

    <nav class="flex gap-6 items-center">
      @foreach($menu as $item)
        <div class="relative group">
          <button class="py-2 font-medium hover:text-indigo-600">
            {{ $item['label'] }}
          </button>

          @if(!empty($item['children']))
            <div class="absolute left-0 top-full hidden group-hover:block min-w-[320px] bg-white border shadow-lg rounded-xl p-2 z-50">
              @foreach($item['children'] as $child)
                @include('partials.nav-item', ['node' => $child])
              @endforeach
            </div>
          @endif
        </div>
      @endforeach
    </nav>

    <div class="flex items-center gap-3">
      <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>

      @role('Admin')
        <a href="{{ route('admin.slides.index') }}" class="text-sm px-3 py-1.5 rounded bg-gray-900 text-white">
          Admin
        </a>
      @endrole

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="text-sm px-3 py-1.5 rounded border">Salir</button>
      </form>
    </div>
  </div>
</header>