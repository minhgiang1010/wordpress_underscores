jQuery(function ($) {
    const slidesOpt         = document.querySelector('.thumbs-wrapper'); 
    const slides            = Array.from( slidesOpt.children );
    const cptParent         = document.getElementsByClassName('thumbs-wrapper')[0];
    const dataIdParent      = cptParent.getAttribute('data-id-parent');
    // data
    const titleSlide        = document.getElementById('title-slide');
    const descSlide         = document.getElementById('description-slide');
    const imgSlide          = document.getElementsByClassName('thumb')[0];
    const img               = imgSlide.children[0];
    // button
    const addSlide          = document.getElementById('add-slide');
    const updateSlide       = document.getElementById('update-slide');
    const cancleSlide       = document.getElementById('cancel-slide');
    // mediaUploader
    var mediaUploader;

    // Remove all selected class in slides
    const removeSelected = ( slide ) => {
        if ( slide.classList.contains('selected') ) {
            slide.classList.remove('selected');
        }
    }
    slides.forEach( removeSelected );
    // End Remove all selected class in slides

    // Set default button status
    const btnStatus = ( boolean ) => {
        if ( boolean ) {
            addSlide.style.display = 'block';
            updateSlide.style.display = 'none';
        } else {
            addSlide.style.display = 'none';
            updateSlide.style.display = 'block';
        }
    }
    btnStatus( true );
    // End Set default button status

    // mediaUploader
    $('.thumb').on('click', function(e) {
        e.preventDefault();
        if( mediaUploader ) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Set a Slide Image',
            button: {
                text: 'Choose Picture'
            },
            multiple: false
        })

        mediaUploader.on( 'select', function() {
            attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
            $('.thumb img').attr( 'src', attachment.url );
            $('.thumb').attr('data-image', attachment.url );

		});
        mediaUploader.open();
    });
    // End mediaUploader

    // get
    slidesOpt.addEventListener('click', e => {
        var sliderTarget = e.target.closest('li'); // strick

        if ( !sliderTarget ) return;

        // remove selected to set it againt :D
        slides.forEach( removeSelected );
        sliderTarget.classList.add('selected');

        if ( sliderTarget.classList.contains('add-new-btn') ) {
            titleSlide.value            = '';
            descSlide.value             = '';
            // imgSlide.dataset.image   = '';
            img.setAttribute( 'src', '' );

            btnStatus( true );
        } else {
            // console.log( sliderTarget.getAttribute('data-id') );
            // console.log( sliderTarget.dataset.id );
            var dataId = sliderTarget.getAttribute('data-id');

            btnStatus( false );
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    'action'        : 'get_slide_cpt',
                    'idParent'      : dataIdParent,
                    'id'            : dataId
                },
                success: function (response) {
                    if ( !response ) return;
                    var objSlide = JSON.parse(response);

                    titleSlide.value        = objSlide.post_title;
                    descSlide.value         = objSlide.post_content;
                    // Lấy post excerpt chứa đường dẫn img :D
                    imgSlide.dataset.image  = objSlide.post_excerpt;
                    img.setAttribute( 'src', objSlide.post_excerpt );
                    updateSlide.dataset.id  = dataId;
                }
            });
        }
    });

    // add
    addSlide.addEventListener('click',  e => {
        e.preventDefault();

        var imgUrl  = imgSlide.dataset.image;
        var title       = titleSlide.value;
        var description = descSlide.value;

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action'        : 'add_slide_cpt',
                'img'           : imgUrl,
                'id'            : dataIdParent,
                'title'         : title,
                'description'   : description
            },
            success: function (response) {
                if ( response !== 'added' ) return;
                location.reload();
            }
        });

    }, { once: true });

    // update
    updateSlide.addEventListener('click', e => { 
        e.preventDefault();

        var imgUrl      = imgSlide.dataset.image;
        var title       = titleSlide.value;
        var description = descSlide.value;
        var id          = updateSlide.dataset.id;

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action'        : 'update_slide_cpt',
                'img'           : imgUrl,
                'id'            : id,
                'title'         : title,
                'description'   : description
            },
            success: function (response) {
                if ( response !== 'updated' ) return;
                location.reload();
            }
        });

    });

    // delete
    slidesOpt.addEventListener('click', e => {
        var sliderTarget = e.target.closest('li');
        var deleteTarget = e.target.classList.contains('delete-btn'); 

        if ( deleteTarget ) {

            var dataId = sliderTarget.getAttribute('data-id');

            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    'action'        : 'delete_slide_cpt',
                    'id'            : dataId,
                },
                success: function ( id ) {
                    titleSlide.value            = '';
                    descSlide.value             = '';
                    img.setAttribute( 'src', '' );
                    btnStatus( true );

                    $('.slide-'+id+'').remove();
                }
            });
        }
    });

});