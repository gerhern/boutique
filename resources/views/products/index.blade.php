<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
  </head>
  <body>
    <h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>
<x-ui.badge status="available"></x-ui.badge>
<x-ui.badge status="reserved"></x-ui.badge>
<x-ui.badge status="sold"></x-ui.badge>
<x-ui.badge status="low_stock"></x-ui.badge>
<x-ui.badge status="raffle"></x-ui.badge>
<h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>
    <x-ui.button variant="primary" size="sm">Ver</x-ui.button>
    <x-ui.button variant="secondary" size="md">Ver</x-ui.button>
    <x-ui.button variant="ghost" size="lg">Ver</x-ui.button>
    <x-ui.button variant="danger" size="sm">Ver</x-ui.button>
    <x-ui.button variant="danger" size="sm" :disabled="true">Ver</x-ui.button>

    <h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>
    <x-ui.input name="name" label="Nombre del producto" hint="Máximo 120 caracteres" />
<x-ui.input name="price" label="Precio" type="number" :error="$errors->first('price')" />
<x-ui.input name="text" label="Texto" type="text" error="Texto invalido" />
<h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>
<x-ui.select
    name="category_id"
    label="Categoría"
    :options="['perro', 'gato']"
    :selected="old('category_id')"
/>
<h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>
<x-ui.alert type="success" title="Guardado" message="El producto fue publicado." />
<x-ui.alert type="danger" title="Error" message="Revisa los campos e intenta de nuevo." />
<x-ui.alert type="warning" title="Stock bajo" message="Quedan 2 piezas disponibles." />
<x-ui.alert type="info" title="Rifa activa" message="La rifa cierra en 3 días." />

  </body>
</html>