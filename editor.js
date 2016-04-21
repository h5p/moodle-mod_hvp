(function ($) {
  $(document).ready(function () {
    var $editor = $('.h5p-editor');
    H5PEditor.init(
      $('#mform1'),
      $('input[name="h5paction"]'),
      $('#fitem_id_h5pfile'),
      $editor.parent().parent(), // Static elements have no custom id or class
      $editor,
      $('input[name="h5plibrary"]'),
      $('input[name="h5pparams"]')
    );
  });
})(H5P.jQuery);
