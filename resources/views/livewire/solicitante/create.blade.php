<div>
    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>


    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un soliciante
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="nombre" wire:keyup="update('nombre')" type="text" class="w-full"></x-input>
                @error('nombre')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido paterno:"></x-label>
                <x-input wire:model="apellido_p" wire:keyup="update('apellido_p')" type="text" class="w-full"></x-input>
                @error('apellido_p')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input wire:model="apellido_m" wire:keyup="update('apellido_m')" type="text" class="w-full"></x-input>
                @error('apellido_m')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Asignar un area:"></x-label>
                <select wire:model.live="id_area2"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">{{'Eliga un area'}}</option>
                    @foreach ($areas as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_area2')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Tipo de solicitante:"></x-label>
                <select wire:model.live="tipo"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Eliga una opción</option>
                    <option value="Docente">Docente</option>
                    <option value="Alumno">Alumno</option>
                </select>
                @error('tipo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            @if ($tipo === 'Alumno')
            <div class="mb-4">
                <x-label value="Número de control:"></x-label>
                <x-input wire:model="numero_control" type="text" class="w-full"></x-input>
                @error('numero_control')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button wire:click="confirmSave2" wire:loading.remove wire:target="confirmSave2">
                    Agregar
                    </x-danger-button>
                    <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        Livewire.on('showConfirmation2', (dato) => {

            const datos = dato[0];
            const name = `${datos.newDatos.nombre} ${datos.newDatos.apellido_p} ${datos.newDatos.apellido_m}`;
            const area = datos.area_nombre;
            const tipo = datos.newDatos.tipo;
            const numero_control = datos.newDatos.numero_control;


        // Prepara el contenido HTML para la alerta
        let htmlContent = `
            <table style="width: 100%; text-align: left;">
                <tr><td><strong>Nombre completo: </strong>${name}</td></tr>
                <tr><td><strong>Area: </strong>${area}</td></tr>
                <tr><td><strong>Tipo de solicitante: </strong>${tipo}</td></tr>
        `;

        // Agrega el número de control solo si el tipo es "Alumno"
        if (tipo === 'Alumno') {
            htmlContent += `<tr><td><strong>No. Control: </strong>${numero_control}</td></tr>`;
        }

        htmlContent += `</table>`;

            Swal.fire({
                title: "¿Estás seguro de agregar este registro?",
                html: htmlContent,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('saveConfirmed2');
                }
            });
        });
    </script>
    @endpush

</div>



{{-- <div class="mb-4">
    <x-label value="Asignar un area:"></x-label>
    <select name="id_area2" id="id_area2-{{ $dato['id'] ?? 'new' }}" wire:model="id_area2"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        @foreach ($areas as $id => $nombre)
        <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
        @endforeach
    </select>
    @error('id_area2')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div> --}}