<div data-type="component-text"
     data-preview="{{!function_exists('themeAsset')?'':themeAsset('img/snippets/preview/home/homepage_banner.jpg')}}"
     data-aoraeditor-title="HomePage Default Banner" data-aoraeditor-categories="Home Page;Banner">
    <link rel="stylesheet" href="{{themeAsset('css/homepageV7/banner-v7.css')}}">
    <div class="banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-2 order-md-1 text-center text-md-start">
                    <h1>La piattaforma di apprendimento delle competenze tecnologiche più affidabile e degna di fiducia</h1>
                    <p>Con i nostri corsi interattivi, puoi esplorare un'infinita gamma di possibilità di apprendimento da leader di pensiero e professionisti del settore che ti aiuteranno a sviluppare nuove competenze e a realizzare il tuo pieno potenziale.. </p>

                    <div class="banner-area-btns">
                        <a href="{{route('login')}}" class="primary-btn">Inizia <i class="ti-arrow-right"></i></a>
                        <a href="{{route('register')}}" class="secondary-btn">Iscriviti ora <i
                                class="ti-arrow-right"></i></a>
                    </div>
                </div>

                <div class="col-md-6 order-1 order-md-2 text-center banner-image">
                    <img src="{{asset('public/frontend/infixlmstheme/img/banner/banner-default.jpg')}}"
                         alt="banner">
                </div>
            </div>
        </div>
    </div>
</div>
