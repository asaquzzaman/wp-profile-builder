    var $ = jQuery;
    /**
     * Upload handler helper
     *
     * @param string browse_button ID of the pickfile
     * @param string container ID of the wrapper
     */
    window.WPUP_Uploader = function (browse_button, container, component) {
        this.container = container;
        this.browse_button = browse_button;
        
        //if no element found on the page, bail out
        if(!$('#'+browse_button).length) {
            return;
        }

        this.component = component;

        //instantiate the uploader
        this.uploader = new plupload.Uploader({
            dragdrop: true,
            drop_element: 'wpup-upload-container',
            runtimes: 'html5,silverlight,flash,html4',
            browse_button: browse_button,
            container: container,
            multipart: true,
            multipart_params: [],
            multiple_queues: false,
            urlstream_upload: true,
            file_data_name: 'wpup_attachment',
            max_file_size: wpup.plupload.max_file_size,
            url: wpup.plupload.url,
            flash_swf_url: wpup.plupload.flash_swf_url,
            silverlight_xap_url: wpup.plupload.silverlight_xap_url,
            filters: wpup.plupload.filters,
            resize: wpup.plupload.resize,
        });

        //attach event handlers
        this.uploader.bind('Init', $.proxy(this, 'init'));
        this.uploader.bind('FilesAdded', $.proxy(this, 'added'));
        this.uploader.bind('QueueChanged', $.proxy(this, 'upload'));
        this.uploader.bind('UploadProgress', $.proxy(this, 'progress'));
        this.uploader.bind('Error', $.proxy(this, 'error'));
        this.uploader.bind('FileUploaded', $.proxy(this, 'uploaded'));

        this.uploader.init();
    };

    WPUP_Uploader.prototype = {

        init: function (up, params) {

        },

        added: function (up, files) {
            var $container = $('#' + this.container).find('.wpup-upload-filelist');

            $.each(files, function(i, file) {
                $container.append(
                    '<div class="upload-item" id="' + file.id + '"><div class="progress"><div class="percent">0%</div><div class="bar"></div></div><div class="filename original">' +
                    file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '</div></div>');
            });

            up.refresh(); // Reposition Flash/Silverlight
            // up.start();
        },

        upload: function (uploader) {
            this.uploader.start();
        },

        progress: function (up, file) {
            var item = $('#' + file.id);

            $('.bar', item).width( file.percent + '%' );

            $('.percent', item).html( file.percent + '%' );
        },

        error: function (up, error) {
            $('#' + this.container).find('#' + error.file.id).remove();
            alert('Error #' + error.code + ': ' + error.message);
        },

        uploaded: function (up, file, response) {
            var res = $.parseJSON(response.response),
                upload_type = $('#'+this.browse_button).data('upload_type'); 
            
            $('#' + file.id + " b").html("100%");
            $('#' + file.id).remove();

            if(res.success) {
                this.component.$root.$emit( 'wpup_file_upload_hook', { res: res.data, upload_type: upload_type } );
            } else {
                alert(res.error);
            }
        }
    };



