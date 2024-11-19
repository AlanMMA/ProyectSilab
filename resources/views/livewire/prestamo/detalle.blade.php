<div class="h-full overflow-y-auto w-full">
    <div class="relative shadow-md w-full">
        <div class="py-4 px-6 block items-center gap-4 w-full sm:flex">
            <div class="px-6 overflow-y-auto max-h-[60vh] sm:max-h-full">
                <div class="px-6 py-2 whitespace-nowrap dark:text-white">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Detalle del préstamo</h2>
                    <p><strong>Prestamo:</strong> {{ $prestamo->id }}</p>
                    <p><strong>Fecha de Préstamo:</strong> {{ $prestamo->fecha }}</p>
                    <p><strong>Solicitante:</strong> {{ $prestamo->solicitante->nombre_completo }}</p>
                    <p><strong>Tipo de solicitante:</strong> {{ $prestamo->solicitante->tipo }}</p>
                    @if ($prestamo->solicitante->tipo === 'alumno')
                    <!-- Verifica si el tipo es 'alumno' -->
                    <p><strong>No. Control:</strong> {{ $prestamo->solicitante->numero_control }}</p>
                    @endif
                </div>
            </div>
            @if ($detalles->count())
            <div class="px-6 overflow-y-auto max-h-[60vh] sm:max-h-full">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                    <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-full">
                        <tr>
                            <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                                wire:click="order('fecha_devolucion')">
                                <div class="flex items-center justify-center">
                                    Devolución
                                    @if ($sort == 'fecha_devolucion')
                                    @if ($direc == 'asc')
                                    <span class="material-symbols-outlined">vertical_align_bottom</span>
                                    @else
                                    <span class="material-symbols-outlined">vertical_align_top</span>
                                    @endif
                                    @else
                                    <span class="material-symbols-outlined">unfold_more</span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                                wire:click="order('material_nombre')">
                                <div class="flex items-center justify-center">
                                    Material
                                    @if ($sort == 'material_nombre')
                                    @if ($direc == 'asc')
                                    <span class="material-symbols-outlined">vertical_align_bottom</span>
                                    @else
                                    <span class="material-symbols-outlined">vertical_align_top</span>
                                    @endif
                                    @else
                                    <span class="material-symbols-outlined">unfold_more</span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('cantidad')">
                                <div class="flex items-center justify-center">
                                    Cantidad
                                    @if ($sort == 'cantidad')
                                    @if ($direc == 'asc')
                                    <span class="material-symbols-outlined">vertical_align_bottom</span>
                                    @else
                                    <span class="material-symbols-outlined">vertical_align_top</span>
                                    @endif
                                    @else
                                    <span class="material-symbols-outlined">unfold_more</span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    Observación
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalles as $detalle)
                        <tr wire:key="material-{{ $detalle->id }}"
                            class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $detalle->fecha_devolucion }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                @if ($detalle->material)
                                {{ $detalle->material->nombre }}
                                @else
                                    Elemento borrado
                                @endif
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $detalle->cantidad }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $detalle->observacion }}
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            @endif
        </div>
    </div>
</div>