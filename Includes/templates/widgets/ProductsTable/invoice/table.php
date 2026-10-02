<style>
    .invoice-table {
        table-layout: fixed!important;
        width: 100% !important;
        border-collapse: collapse!important;
        border-style: inherit!important;
    }
    .invoice-table td {
        overflow: hidden!important;
    }
    .invoice-table .holder {
        display: flex!important;
        align-items: center!important;
    }
    .invoice-table .holder i,.invoice-table .holder svg{
        margin: 0 2px!important;
    }
    .invoice-table .product-invoice-image-thumb img {
        width: auto!important;
        height: auto!important;
        max-width: 100% !important;
        max-height: 100% !important;
        object-fit: contain!important;
    }

</style>
<table class="invoice-table">
	<thead>
		<?php include 'thead.php'; ?>
	</thead>

    <tbody>
        <?php include 'tbody.php'; ?>
    </tbody>

</table>

<script>
    (function ($) {
        "use strict";

        let barcode_id_elements = $( '.ot-barcode-img' );
        let qrcode_id_elements = $( '.ot-qrcode-img' );

        for (let i = 0; i < barcode_id_elements.length; i++) {
            let barcode_value = $( barcode_id_elements ).eq( i ).attr( 'data-id' );
            let id = $( barcode_id_elements ).eq(i).attr( 'id' );
            if ( barcode_value === undefined || barcode_value === '' ) {
                continue;
            }

            JsBarcode( `#${id}` , barcode_value , {
                format : "CODE128" ,
                height : 55 ,
                textMargin : 4 ,
                lineColor : "<?php echo $settings['tbody_font_color']; ?>",
            });

        }

        for (let i = 0; i < qrcode_id_elements.length; i++) {
            let qrcode_value = $( qrcode_id_elements ).eq( i ).attr( 'data-id' );
            let id = $( qrcode_id_elements ).eq(i).attr( 'id' );
            if ( qrcode_value === undefined || qrcode_value === '' ) {
                continue;
            }

            QR_maker( qrcode_value , id );

        }

        function QR_maker( value , element_id = '' ){
            const qrCode = new QRCodeStyling({
                data: value ,
                dotsOptions : {
                    type : 'rounded' ,
                    color : '<?php echo $settings['tbody_font_color']; ?>'
                } ,
                backgroundOptions: {
                    color: "transparent",
                },
                qrOptions: {
                    typeNumber : 5 ,
                    mode: 'Byte',
                    errorCorrectionLevel: 'H'
                },
            });

            qrCode.getRawData("PNG").then( ( buffer ) => {
                buffer = URL.createObjectURL( buffer );
                let elem = document.getElementById( element_id );
                elem.setAttribute( 'src' , buffer )
            });
        }

    })(jQuery);

</script>