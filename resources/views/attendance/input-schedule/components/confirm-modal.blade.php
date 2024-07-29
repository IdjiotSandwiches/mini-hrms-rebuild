<confirm-modal class="
    confirm-modal hidden z-30 fixed bg-black backdrop-blur-sm bg-opacity-30 w-screen h-screen left-0 top-0 text-center
    dark:bg-opacity-0
    ">
    <div class="
        flex justify-center items-center h-full px-10
        md:px-28
    ">
        <div class="
            grid bg-white px-8 py-6 w-full rounded-md gap-8 select-none
            dark:bg-[#121212] dark:border-2
            lg:w-1/2
        ">
            <div class="flex flex-col gap-5 items-center">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="fill-red-500 h-32 w-32">
                    <path d="M13 17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17C11 16.4477 11.4477 16 12 16C12.5523 16 13 16.4477 13 17Z"/>
                    <path clip-rule="evenodd" d="M12 6C12.5523 6 13 6.44772 13 7V13C13 13.5523 12.5523 14 12 14C11.4477 14 11 13.5523 11 13V7C11 6.44772 11.4477 6 12 6Z" fill-rule="evenodd"/>
                    <path clip-rule="evenodd" d="M12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z" fill-rule="evenodd"/>
                </svg>
                <h1 class="text-3xl font-semibold">Are you sure?</h1>
                <p>You cannot change your schedule in 3 months!</p>
                <form class="flex justify-center gap-5">
                    @csrf
                    <button id="confirm-schedule" class="py-2 px-8 text-white text-lg rounded-md bg-blue-600 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">Yes</button>
                    <button id="cancel" class="py-2 px-8 text-white text-lg rounded-md bg-red-600 hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">No</button>
                </form>
            </div>
        </div>
    </div>
</confirm-modal>
