//hooks to yasrBuilderBegin to disable ranking options
wp.hooks.addAction('yasrBuilderBegin', 'yet-another-stars-rating', yasrEnableRankingOptions);

/**
 * I've to enable the Parents class but not the childs
 *
 * @param classParents
 * @param classChilds
 */
function yasrEnableRankingOptions (classParents, classChilds) {
    jQuery(classParents).prop('disabled', false); //enable everything

    //Enable the calendar
    jQuery(classParents).find('input').each(function () {
        jQuery(this).prop('disabled', false);
    });
}

wp.hooks.addAction('yasrStyleOptions', 'yet-another-stars-rating', yasrProStyleOptions);

function yasrProStyleOptions () {
    //show media loader
    jQuery(document).ready(function () {
        var $ = jQuery;
        if ($('.yasr-pro-upload-image').length > 0) {
            if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $(document).on('click', '.yasr-pro-upload-image', function (e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    wp.media.editor.send.attachment = function (props, attachment) {
                        id.val(attachment.url);
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            }
        }

        $('.yasr-pro-input-text-upload-image').on('click', function () {
            $(this).val('');
        });

        $('#yasr-st-reset-stars').on('click', function () {
            $('.yasr-pro-input-text-upload-image').val('');
            $('.yasr_uploaded_stars_preview').hide();
            $('#yasr_pro_choosen_stars_0yasr').prop("checked", true);
            return false;
        });
    });
}
