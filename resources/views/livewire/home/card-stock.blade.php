<div class="bg-white dark:bg-gray-800 text-lg  mb-3 sm:w-sm">
    <p class="text-black dark:text-white text-center font-semibold text-xl pb-2">Materiales con poco stock:</p>
    <ul class="text-black dark:text-white list-disc pl-4">
        @foreach ($materiales as $material)
            <li class="ml-0 text-base leading-tight flex items-center relative">
                <span class="mr-2 text-black dark:text-white text-xs font-normal">•</span>
                {{ $material->nombre }} - Stock: {{ $material->stock }}
            </li>
        @endforeach
    </ul>
</div>