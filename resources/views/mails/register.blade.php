@component('mail::layout')
    <style>
        .wrapper .content {background-color:#64C995 !important;}
        .header a {color:#FFFFFF !important;}
        .body .inner-body .content-cell {color:#000000 !important;}
        table.footer td.content-cell p {color:#FFFFFF !important;}
    </style>
    @slot('header')
        @component('mail::header', ['url' => ''])
            <img src="http://sharefy.tk/images/logojusto.png" alt="logojusto_register" width="80" /><br />
            Sharefy
        @endcomponent
    @endslot

    Benvingut a SHAREFY {{$username}}!

    Gràcies per utilitzar el nostre servei, no et defraudarem!

    Att. Team Sharefy

    @slot('footer')
        @component('mail::footer')
            © 2019 Sharefy. All rights reserved.
        @endcomponent
    @endslot
@endcomponent