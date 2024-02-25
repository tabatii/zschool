<x-layouts.app>
    <section @class([
        'relative flex items-center min-h-[calc(100vh-8rem)] pb-32',
        'after:absolute after:-top-[350px] after:end-[25vw] after:z-[-2]',
        'after:rotate-[30deg] after:bg-orange-50 after:rounded-full after:h-[750px] after:w-[2000px]',
    ])>
        <div class="container px-3">
            <div class="flex flex-wrap gap-y-8 -mx-3">
                <div class="w-full lg:w-7/12 order-2 lg:order-1 px-3">
                    <div class="flex items-center h-full">
                        <div class="w-full lg:pe-32">
                            <h1 class="text-5xl mb-8">Our aim is to digitize education in Africa.</h1>
                            <p class="text-xl mb-12">E-school is a platform interested in enhancing parent and teacher co-operation in Africa.</p>
                            <div>
                                <a href="#login" @class([
                                    'inline-flex bg-orange-500 hover:bg-orange-300 text-white text-lg font-medium',
                                    'rounded transition-colors duration-500 px-12 py-4',
                                ])>
                                    <span>Get started</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative w-full lg:w-5/12 order-1 lg:order-2 px-3">
                    <div class="absolute lg:-inset-4 xl:-inset-12 hidden lg:flex justify-between -rotate-[35deg] z-[-1]">
                        <div class="flex items-center">
                            <div class="bg-teal-500 rounded-full h-52 w-12"></div>
                            <div class="bg-blue-500 rounded-full h-72 w-12"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-cyan-500 rounded-full h-72 w-12"></div>
                            <div class="bg-orange-500 rounded-full h-52 w-12"></div>
                        </div>
                    </div>
                    <img
                        src="{{ asset('assets/hero.jpg') }}"
                        class="relative rounded-tl-[50px] rounded-tr-[150px] rounded-bl-[150px] rounded-br-[50px] w-full"
                        draggable="false"
                    />
                </div>
            </div>
        </div>
    </section>
    <section class="py-24" id="login">
        <div class="container px-3">
            <h2 class="text-4xl text-center mb-8 sm:mb-16">Sign in to the platform</h2>
            <div class="flex flex-wrap gap-y-6 -mx-3">
                <div class="w-full sm:w-6/12 lg:w-3/12 px-3">
                    <a href="{{ panel('admin')->getLoginUrl() }}" class="relative block pb-[100%]">
                        <div class="absolute inset-0 flex items-center bg-orange-50 text-orange-500 rounded-xl">
                            <div class="w-full">
                                <div class="flex justify-center mb-4">
                                    <div class="inline-flex justify-center items-center bg-white rounded-full h-28 w-28">
                                        <i class="fa-solid fa-user-tie text-6xl"></i>
                                    </div>
                                </div>
                                <p class="text-xl text-center">Administration Login</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-6/12 lg:w-3/12 px-3">
                    <a href="{{ panel('teacher')->getLoginUrl() }}" class="relative block pb-[100%]">
                        <div class="absolute inset-0 flex items-center bg-blue-50 text-blue-500 rounded-xl">
                            <div class="w-full">
                                <div class="flex justify-center mb-4">
                                    <div class="inline-flex justify-center items-center bg-white rounded-full h-28 w-28">
                                        <i class="fa-solid fa-person-chalkboard text-6xl"></i>
                                    </div>
                                </div>
                                <p class="text-xl text-center">Teacher Login</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-6/12 lg:w-3/12 px-3">
                    <a href="{{ panel('student')->getLoginUrl() }}" class="relative block pb-[100%]">
                        <div class="absolute inset-0 flex items-center bg-cyan-50 text-cyan-500 rounded-xl">
                            <div class="w-full">
                                <div class="flex justify-center mb-4">
                                    <div class="inline-flex justify-center items-center bg-white rounded-full h-28 w-28">
                                        <i class="fa-solid fa-graduation-cap text-6xl"></i>
                                    </div>
                                </div>
                                <p class="text-xl text-center">Student Login</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-6/12 lg:w-3/12 px-3">
                    <a href="{{ panel('guardian')->getLoginUrl() }}" class="relative block pb-[100%]">
                        <div class="absolute inset-0 flex items-center bg-teal-50 text-teal-500 rounded-xl">
                            <div class="w-full">
                                <div class="flex justify-center mb-4">
                                    <div class="inline-flex justify-center items-center bg-white rounded-full h-28 w-28">
                                        <i class="fa-solid fa-house-user text-6xl"></i>
                                    </div>
                                </div>
                                <p class="text-xl text-center">Parent Login</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-cover bg-center bg-fixed h-[300px]" style="background-image:url('/assets/bg.jpg')"></section>
    <section class="py-24">
        <div class="relative flex items-center h-full">
            <div class="container px-3">
                <div class="flex flex-wrap justify-center -mx-3">
                    <div class="w-full sm:w-8/12 lg:w-6/12 xl:w-4/12 px-3">
                        <livewire:contact-form />
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>