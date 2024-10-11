<div class="relative inline-block">
    <input type="checkbox" id="theme-toggle" onchange="toggleTheme()"
        class="peer relative w-[3.25rem] h-7 p-px bg-gray-100 border-gray-600 text-gray-border-gray-600 rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-red disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:textneutral-700 checked:borderneutral-700 focus:checked:borderneutral-700 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bgneutral-700 dark:checked:borderneutral-700 dark:focus:ring-offset-gray-600 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-blue-200">
    <label for="theme-toggle" class="sr-only">switch</label>
    <span
        class="peer-checked:text-white text-gray-500 size-6 absolute top-0.5 start-0.5 flex justify-center items-center pointer-events-none transition-colors ease-in-out duration-200 dark:text-neutral-500">
        <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="20px" fill="#000">
            <path
                d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm-.23 72Q400-288 344-344.23q-56-56.22-56-136Q288-560 344.23-616q56.22-56 136-56Q560-672 616-615.77q56 56.22 56 136Q672-400 615.77-344q-56.22 56-136 56ZM216-444H48v-72h168v72Zm696 0H744v-72h168v72ZM444-744v-168h72v168h-72Zm0 696v-168h72v168h-72ZM269-642L166-742l51-55 102 104-50 51Zm474 475L642-268l49-51 103 101-51 51ZM640-691l102-101 51 49-100 103-53-51ZM163-217l105-99 49 47-98 104-56-52Zm317-263Z" />
        </svg>
    </span>
    <span
        class="peer-checked:textneutral-700 text-gray-500 size-6 absolute top-0.5 end-0.5 flex justify-center items-center pointer-events-none transition-colors ease-in-out duration-200 dark:text-neutral-500">

        <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="20px" fill="#000">
            <path
                d="M576-96q-78.72 0-148.8-30.24-70.08-30.24-122.4-82.56-52.32-52.32-82.56-122.4Q192-401.28 192-480q0-79.68 30.24-149.28T304.8-751.2q52.32-52.32 122.4-82.56Q497.28-864 576-864q51.23 0 99.62 12.5Q724-839 768-812.67 680-759 628-671q-52 88-52 191t52 191q52 88 140 141.67-44 26.33-92.38 38.83Q627.23-96 576-96Zm0-72q12 0 24-1t24-3q-57-65-88.5-143.63T504-480.13q0-85.87 31.5-164.88Q567-724.02 624-788q-12-2-24-3t-24-1q-129.67 0-220.84 91.23-91.16 91.23-91.16 221Q264-350 355.16-259q91.17 91 220.84 91Zm-72-312Z" />
        </svg>

    </span>
</div>


<script>
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';

        if (currentTheme === 'dark') {
            html.classList.remove('dark');
            html.classList.add('light');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.remove('light');
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }

        syncThemeToggles();
    }

    function syncThemeToggles() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        const themeToggle1 = document.getElementById('theme-toggle');
        const themeToggle2 = document.getElementById('theme-toggle2');

        themeToggle1.checked = savedTheme === 'dark';
        themeToggle2.checked = savedTheme === 'dark';
    }

    document.addEventListener('DOMContentLoaded', function() {
        syncThemeToggles();
    });
</script>
