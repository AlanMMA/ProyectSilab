<div class="h-full w-full">
    <div class="flex flex-col items-center gap-4">
        <div class="w-full flex justify-end gap-6">
            <a class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
            wire:click="confirmarSeleccion"> <!-- Llama al método sin parámetros -->
            <span class="material-symbols-outlined text-white">
                close
            </span>
        </a>
            <x-input type="text" wire:model="solicitanteInfo" readonly></x-input>
            <x-input type="date" />
        </div>

    </div>
    <div class="w-full flex justify-center">
        <table class="w-min text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-auto">
                <tr>
                    <th scope="col" class=" px-6 py-3">
                        <div class="flex items-center w-full ">
                            ID
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center w-full">
                            Material
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center w-full">
                            Cantidad
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center w-max">
                            Fecha de devolución
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center w-max">
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
</div>
</div>
