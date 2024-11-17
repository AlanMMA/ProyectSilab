<div>
    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>


    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un nuevo alumno de servicio social
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="nombre" wire:keyup="update('nombre')" type="text" value="{{ old('nombre') }}"
                    class="w-full"></x-input>
                <x-input-error for="nombre"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Apellido paterno:"></x-label>
                <x-input wire:model="apellido_pS" wire:keyup="update('apellido_pS')" type="text"
                    value="{{ old('apellido_pS') }}" class="w-full"></x-input>
                <x-input-error for="apellido_pS"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input wire:model="apellido_mS" wire:keyup="update('apellido_mS')" type="text"
                    value="{{ old('apellido_mS') }}" class="w-full"></x-input>
                <x-input-error for="apellido_mS"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Numero de control:"></x-label>
                <x-input wire:model="no_control" wire:keyup="update('no_control')" type="text"
                    value="{{ old('no_control') }}" class="w-full"></x-input>
                <x-input-error for="no_control"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="usuario:"></x-label>
                <x-input wire:model="name" wire:keyup="update('name')" type="text" value="{{ old('name') }}"
                    class="w-full"></x-input>
                <x-input-error for="name"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Correo electronico:"></x-label>
                <x-input wire:model="email" wire:keyup="update('email')" type="text" value="{{ old('email') }}"
                    class="w-full"></x-input>
                <x-input-error for="email"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Contraseña:"></x-label>
                <div class="relative w-full flex items-center">
                    <x-input wire:model="password" wire:keyup="update('password')"
                        :type="$showPassword ? 'text' : 'password'" class="w-full pr-12"></x-input>
                    <button type="button" wire:click="togglePasswordVisibility"
                        class="ml-auto px-3 flex items-center focus:outline-none">
                        @if ($showPassword)
                        <span class="material-symbols-outlined">visibility</span>
                        @else
                        <span class="material-symbols-outlined">visibility_off</span>
                        @endif
                    </button>
                </div>
                <x-input-error for="password"></x-input-error>
            </div>

            <div class="mb-4">
                <x-label value="Confirmar contraseña:"></x-label>
                <div class="relative w-full flex items-center">
                    <x-input wire:model="password_confirmation" wire:keyup="update('password_confirmation')"
                        :type="$showPassword2 ? 'text' : 'password'" class="w-full pr-12"></x-input>
                    <button type="button" wire:click="togglePasswordVisibility2"
                        class="ml-auto px-3 flex items-center focus:outline-none">
                        @if ($showPassword2)
                        <span class="material-symbols-outlined">visibility</span>
                        @else
                        <span class="material-symbols-outlined">visibility_off</span>
                        @endif
                    </button>
                </div>
                <x-input-error for="password_confirmation"></x-input-error> <!-- Nueva validación -->
            </div>

            <div class="mb-4">
                <x-label value="Encargado:"></x-label>
                @auth
                <x-input type="text" class="w-full"
                    value="{{ $nombreE }} {{ $apellido_p }} {{ $apellido_m }} = {{ auth()->user()->id_encargado }}"
                    disabled></x-input>
                <input type="hidden" wire:model="id_encargado">
                @error('id_encargado')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                @endauth
                {{--
                QUITAR COMENTARIOS EN CASO DE ERROR
                <x-label value="Asignar un rol:"></x-label>
                <select name="id_rol" wire:model.live="id_rol"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Seleccione un rol</option>
                    @foreach ($roles as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_rol')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                --}}
            </div>

            {{-- @if ($id_rol == 0)

            @elseif ($id_rol == 1)
            <div class="mb-4">
                <x-label value="Encargado:"></x-label>
                <select name="id_encargado" wire:model="id_encargado"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Seleccione a un encargado</option>
                    @foreach ($encargados as $encargado)
                    <option value="{{ $encargado->id }}">{{ $encargado->nombre }} {{ $encargado->apellido_p }}
                        {{ $encargado->apellido_m }}</option>
                    @endforeach
                </select>
                @error('id_encargado')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            @else
            <div class="mb-4">
                <x-label value="Encargado:"></x-label>
                @auth
                <x-input type="text" class="w-full"
                    value="{{ $nombreE }} {{ $apellido_p }} {{ $apellido_m }} = {{ auth()->user()->id_encargado }}"
                    disabled></x-input>
                <input type="hidden" wire:model="id_encargado">
                @error('id_encargado')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                @endauth
            </div>
            @endif --}}
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button wire:click="confirmSave2" wire:loading.remove wire:target="confirmSave2">
                    Agregar

                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        Livewire.on('showConfirmation2', (dato) => {
            const datos = dato[0];
            const name = datos.newDatos.nombre;
            const last_p = datos.newDatos.apellido_pS;
            const last_m = datos.newDatos.apellido_mS;
            const no_control = datos.newDatos.no_control;

        let htmlContent = `
            <table style="width: 100%; text-align: left;">
                <tr><td><strong>Nombre: </strong>${name}</td></tr>
                <tr><td><strong>Apellido Paterno: </strong>${last_p}</td></tr>
                <tr><td><strong>Apellido Materno: </strong>${last_m}</td></tr>
                <tr><td><strong>Numero de control: </strong>${no_control}</td></tr>
            </table>
        `;

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
                Livewire.dispatch('saveConfirmed2'); // Llama al método save para guardar el nuevo encargado
            }
        });
    });
    </script>
    @endpush


</div>