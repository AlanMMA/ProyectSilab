<div class="h-full overflow-y-auto w-full">
    @if ($alumnos)
    <div class="relative shadow-md w-full">
        <div class="py-4 px-6 block items-center gap-4 w-full sm:flex justify-center">
            @if ($alumnos->count())
            <div class="px-6 overflow-y-auto max-h-[60vh] sm:max-h-full ">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                    <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-full">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    Nombre
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    Apellido paterno
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    Apellido materno
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr wire:key="material-{{ $alumnos->id }}"
                            class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $alumnos->nombre }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $alumnos->apellido_pS }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $alumnos->apellido_mS }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @else
    <p>No se encontraron detalles para el alumno.</p>
    @endif

</div>