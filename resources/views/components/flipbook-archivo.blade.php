@props([
    'pdf'
])

<div class="w-full mt-8 mb-12">

    {{-- HEADER --}}
    <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-8 mb-10">

        <div class="mx-8">

            <div class="badge badge-primary badge-soft px-4 py-3 font-inconsolata mb-5">
                Interactive PDF Viewer
            </div>

            <p class="mt-5 max-w-2xl text-sm md:text-base leading-relaxed text-base-content/60 font-inconsolata">
                Este libro está en tu estantería personal.
            </p>

        </div>

        {{-- DESKTOP CONTROLS --}}
        <div
            id="flipbook-controls"
            class="
                hidden
                md:flex
                items-center
                gap-3
                bg-base-100/80
                backdrop-blur-xl
                border
                border-base-content/10
                rounded-full
                px-3
                py-2
                shadow-xl
            "
        >

            <button
                id="flipbook-prev"
                type="button"
                class="
                    btn
                    btn-circle
                    btn-sm
                    bg-base-100
                    border
                    border-base-content/10
                    hover:bg-primary
                    hover:text-primary-content
                    transition-all
                    duration-300
                "
            >
                <span class="icon-[tabler--chevron-left] text-lg"></span>
            </button>

            <div
                class="
                    h-10
                    px-5
                    rounded-full
                    bg-base-200/70
                    border
                    border-base-content/10
                    flex
                    items-center
                    justify-center
                    text-sm
                    tracking-widest
                    font-inconsolata
                    min-w-[110px]
                "
            >

                <span id="flipbook-current-page">
                    1
                </span>

                <span class="mx-2 opacity-40">
                    /
                </span>

                <span id="flipbook-total-pages">
                    0
                </span>

            </div>

            <button
                id="flipbook-next"
                type="button"
                class="
                    btn
                    btn-circle
                    btn-sm
                    btn-primary
                    shadow-lg
                    hover:scale-105
                    transition-all
                    duration-300
                "
            >
                <span class="icon-[tabler--chevron-right] text-lg"></span>
            </button>

        </div>

    </div>

    {{-- VIEWER --}}
    <div
        class="
            relative
            overflow-hidden
            rounded-[2rem]
            border
            border-base-content/10
            bg-base-100/80
            backdrop-blur-xl
            shadow-[0_20px_80px_rgba(0,0,0,0.12)]
        "
    >

        {{-- DECORATION --}}
        <div
            class="
                absolute
                -top-40
                -right-40
                w-96
                h-96
                rounded-full
                bg-primary/10
                blur-3xl
                pointer-events-none
            "
        ></div>

        <div
            class="
                absolute
                -bottom-52
                -left-40
                w-[30rem]
                h-[30rem]
                rounded-full
                bg-secondary/10
                blur-3xl
                pointer-events-none
            "
        ></div>

        {{-- LOADING --}}
        <div
            id="flipbook-loading"
            class="
                relative
                z-10
                flex
                flex-col
                items-center
                justify-center
                py-24
                px-6
                transition-all
                duration-500
            "
        >

            <div class="relative w-24 h-24">

                <div
                    class="
                        absolute
                        inset-0
                        rounded-full
                        border-[6px]
                        border-base-300
                    "
                ></div>

                <div
                    class="
                        absolute
                        inset-0
                        rounded-full
                        border-[6px]
                        border-transparent
                        border-t-primary
                        animate-spin
                    "
                ></div>

            </div>

            <h3 class="mt-8 text-3xl font-serif tracking-tight">
                Preparando documento
            </h3>

            <p
                id="flipbook-loading-text"
                class="
                    mt-4
                    text-sm
                    md:text-base
                    text-base-content/60
                    font-inconsolata
                    text-center
                "
            >
                Procesando páginas...
            </p>

            <div
                class="
                    w-full
                    max-w-lg
                    h-3
                    rounded-full
                    bg-base-300
                    overflow-hidden
                    mt-8
                "
            >

                <div
                    id="flipbook-progress-bar"
                    class="
                        h-full
                        bg-gradient-to-r
                        from-primary
                        to-secondary
                        flex
                        items-center
                        justify-center
                        text-[10px]
                        font-bold
                        text-primary-content
                        transition-all
                        duration-300
                    "
                    style="width:0%"
                >
                    0%
                </div>

            </div>

        </div>

        {{-- FLIPBOOK --}}
        <div
            id="flipbook-container"
            class="
                hidden
                relative
                z-10
                w-full
                overflow-hidden
            "
        >

            <div
                class="
                    flex
                    justify-center
                    items-center
                    w-full
                    overflow-hidden
                    px-2
                    py-4
                    md:px-8
                    md:py-10
                "
            >

                <div
                    id="flipbook"
                    class="
                        transition-all
                        duration-500
                    "
                ></div>

            </div>

        </div>

    </div>

    {{-- MOBILE CONTROLS --}}
    <div
        id="flipbook-controls-mobile"
        class="
            hidden
            md:hidden
            fixed
            bottom-5
            left-1/2
            -translate-x-1/2
            z-50
        "
    >

        <div
            class="
                flex
                items-center
                gap-3
                rounded-full
                border
                border-base-content/10
                bg-base-100/85
                backdrop-blur-xl
                px-4
                py-3
                shadow-2xl
            "
        >

            <button
                id="flipbook-prev-mobile"
                type="button"
                class="
                    btn
                    btn-circle
                    btn-sm
                    bg-base-100
                    border
                    border-base-content/10
                "
            >
                <span class="icon-[tabler--chevron-left]"></span>
            </button>

            <div
                class="
                    min-w-[70px]
                    text-center
                    text-sm
                    tracking-widest
                    font-inconsolata
                "
            >

                <span id="flipbook-current-page-mobile">
                    1
                </span>

                /

                <span id="flipbook-total-pages-mobile">
                    0
                </span>

            </div>

            <button
                id="flipbook-next-mobile"
                type="button"
                class="
                    btn
                    btn-circle
                    btn-sm
                    btn-primary
                "
            >
                <span class="icon-[tabler--chevron-right]"></span>
            </button>

        </div>

    </div>

</div>

<script>
    window.FLIPBOOK_PDF_URL = @json($pdf);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>

pdfjsLib.GlobalWorkerOptions.workerSrc =
'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const PDF_URL =
    window.FLIPBOOK_PDF_URL;

const flipbook =
    $('#flipbook');

let pdfDoc = null;

let resizeTimeout = null;

/*
|--------------------------------------------------------------------------
| INIT
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'DOMContentLoaded',
    async () => {

        await loadPDF();

    }
);

/*
|--------------------------------------------------------------------------
| LOAD PDF
|--------------------------------------------------------------------------
*/

async function loadPDF() {

    try {

        setLoadingText(
            'Cargando documento...'
        );

        const loadingTask =
            pdfjsLib.getDocument(PDF_URL);

        pdfDoc =
            await loadingTask.promise;

        setLoadingText(
            `Procesando ${pdfDoc.numPages} páginas`
        );

        await renderPages();

    } catch (error) {

        console.error(error);

        setLoadingText(
            'Error cargando PDF'
        );

    }

}

/*
|--------------------------------------------------------------------------
| RENDER PAGES
|--------------------------------------------------------------------------
*/

async function renderPages() {

    const container =
        document.getElementById(
            'flipbook'
        );

    container.innerHTML = '';

    const isMobile =
        window.innerWidth < 768;

    const scale =
        isMobile
            ? 1.15
            : 1.8;

    /*
    |--------------------------------------------------------------
    | 🔥 HOJA BLANCA FORZADA (PRIMERA PÁGINA)
    |--------------------------------------------------------------
    */

    const blankPage =
        document.createElement('div');

    blankPage.className = 'page';
    blankPage.style.background = '#ffffff';
    blankPage.style.borderRadius = '1.2rem';
    blankPage.style.height = '100%';
    blankPage.style.width = '100%';

    container.appendChild(blankPage);

    updateProgress(0);

    for (
        let pageNumber = 1;
        pageNumber <= pdfDoc.numPages;
        pageNumber++
    ) {

        const page =
            await pdfDoc.getPage(pageNumber);

        const viewport =
            page.getViewport({
                scale
            });

        const canvas =
            document.createElement(
                'canvas'
            );

        const context =
            canvas.getContext('2d');

        canvas.width =
            viewport.width;

        canvas.height =
            viewport.height;

        canvas.style.width =
            '100%';

        canvas.style.height =
            '100%';

        canvas.style.display =
            'block';

        canvas.style.objectFit =
            'contain';

        canvas.style.borderRadius =
            '1rem';

        await page.render({

            canvasContext: context,

            viewport: viewport

        }).promise;

        const pageWrapper =
            document.createElement(
                'div'
            );

        pageWrapper.className =
            'page';

        pageWrapper.style.background =
            'linear-gradient(to right, #ffffff, #fafafa)';

        pageWrapper.style.borderRadius =
            '1.2rem';

        pageWrapper.style.overflow =
            'hidden';

        pageWrapper.style.boxShadow =
            '0 15px 40px rgba(0,0,0,0.12)';

        pageWrapper.style.border =
            '1px solid rgba(0,0,0,0.05)';

        pageWrapper.style.padding =
            isMobile
                ? '0.35rem'
                : '0.75rem';

        pageWrapper.appendChild(
            canvas
        );

        container.appendChild(
            pageWrapper
        );

        updateProgress(
            pageNumber
        );

    }

    initializeFlipbook();

}

/*
|--------------------------------------------------------------------------
| CONFIG
|--------------------------------------------------------------------------
*/

function getFlipbookConfig() {

    const screenWidth =
        window.innerWidth;

    if (screenWidth < 768) {

        const width =
            Math.min(
                screenWidth - 24,
                420
            );

        return {

            width,

            height:
                Math.round(
                    width * 1.45
                ),

            display:
                'single'

        };

    }

    const width =
        Math.min(
            screenWidth - 80,
            1200
        );

    return {

        width,

        height:
            Math.round(
                width * 0.65
            ),

        display:
            'double'

    };

}

/*
|--------------------------------------------------------------------------
| INITIALIZE FLIPBOOK
|--------------------------------------------------------------------------
*/

function initializeFlipbook() {

    const config =
        getFlipbookConfig();

    if (flipbook.data('turn')) {

        flipbook.turn(
            'destroy'
        );

    }

    flipbook.turn({

        width:
            config.width,

        height:
            config.height,

        display:
            config.display,

        autoCenter:
            true,

        acceleration:
            true,

        gradients:
            true,

        elevation:
            80,

        duration:
            700,

        when: {

            turning: function () {

                flipbook.addClass(
                    'scale-[0.995]'
                );

            },

            turned: function () {

                flipbook.removeClass(
                    'scale-[0.995]'
                );

                updateCounter();

            }

        }

    });

    $('#flipbook .page').css({

        width: '100%',

        height: '100%'

    });

    flipbook.css({

        filter:
            'drop-shadow(0 30px 50px rgba(0,0,0,0.18))'

    });

    showViewer();

    bindControls();

    updateCounter();

}

/*
|--------------------------------------------------------------------------
| SHOW VIEWER
|--------------------------------------------------------------------------
*/

function showViewer() {

    const loading =
        document.getElementById(
            'flipbook-loading'
        );

    loading.classList.add(
        'opacity-0'
    );

    setTimeout(() => {

        loading.style.display =
            'none';

    }, 400);

    document
        .getElementById(
            'flipbook-container'
        )
        .classList.remove(
            'hidden'
        );

    if (
        window.innerWidth >= 768
    ) {

        document
            .getElementById(
                'flipbook-controls'
            )
            .classList.remove(
                'hidden'
            );

    } else {

        document
            .getElementById(
                'flipbook-controls-mobile'
            )
            .classList.remove(
                'hidden'
            );

    }

}

/*
|--------------------------------------------------------------------------
| CONTROLS
|--------------------------------------------------------------------------
*/

function bindControls() {

    $('#flipbook-next, #flipbook-next-mobile')
        .off('click')
        .on('click', () => {

            flipbook.turn(
                'next'
            );

        });

    $('#flipbook-prev, #flipbook-prev-mobile')
        .off('click')
        .on('click', () => {

            flipbook.turn(
                'previous'
            );

        });

    document.removeEventListener(
        'keydown',
        keyboardNavigation
    );

    document.addEventListener(
        'keydown',
        keyboardNavigation
    );

}

function keyboardNavigation(event) {

    if (!flipbook.data('turn')) {
        return;
    }

    if (event.key === 'ArrowRight') {
        flipbook.turn('next');
    }

    if (event.key === 'ArrowLeft') {
        flipbook.turn('previous');
    }

}

/*
|--------------------------------------------------------------------------
| COUNTER
|--------------------------------------------------------------------------
*/

function updateCounter() {

    const current =
        flipbook.turn('page');

    const total =
        flipbook.turn('pages');

    $('#flipbook-current-page')
        .text(current);

    $('#flipbook-total-pages')
        .text(total);

    $('#flipbook-current-page-mobile')
        .text(current);

    $('#flipbook-total-pages-mobile')
        .text(total);

}

/*
|--------------------------------------------------------------------------
| LOADING
|--------------------------------------------------------------------------
*/

function setLoadingText(text) {

    document.getElementById(
        'flipbook-loading-text'
    ).textContent = text;

}

function updateProgress(pageNumber) {

    const progress =
        Math.round(
            (
                pageNumber /
                pdfDoc.numPages
            ) * 100
        );

    const progressBar =
        document.getElementById(
            'flipbook-progress-bar'
        );

    progressBar.style.width =
        `${progress}%`;

    progressBar.textContent =
        `${progress}%`;

}

/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'resize',
    () => {

        clearTimeout(
            resizeTimeout
        );

        resizeTimeout =
            setTimeout(() => {

                if (!flipbook.data('turn')) {
                    return;
                }

                const currentPage =
                    flipbook.turn(
                        'page'
                    );

                initializeFlipbook();

                flipbook.turn(
                    'page',
                    currentPage
                );

            }, 300);

    }
);

</script>