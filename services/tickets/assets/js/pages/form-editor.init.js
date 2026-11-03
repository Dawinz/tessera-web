/**
 * Dashonic form-editor demo init - only run after DOM is ready and targets exist.
 */
document.addEventListener('DOMContentLoaded', function () {
  if (typeof ClassicEditor !== 'undefined') {
    var classicEl = document.querySelector('#ckeditor-classic');
    if (classicEl) {
      ClassicEditor.create(classicEl)
        .then(function (editor) {
          editor.ui.view.editable.element.style.height = '200px';
        })
        .catch(function (err) {
          console.error(err);
        });
    }
  }

  if (typeof Quill === 'undefined') {
    return;
  }

  var snowEl = document.querySelector('#snow-editor');
  if (snowEl) {
    try {
      new Quill(snowEl, {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ font: [] }, { size: [] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ script: 'super' }, { script: 'sub' }],
            [{ header: [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'],
            [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
            ['direction', { align: [] }],
            ['link', 'image', 'video'],
            ['clean']
          ]
        }
      });
    } catch (err) {
      console.error(err);
    }
  }

  var bubbleEl = document.querySelector('#bubble-editor');
  if (bubbleEl) {
    try {
      new Quill(bubbleEl, { theme: 'bubble' });
    } catch (err) {
      console.error(err);
    }
  }
});
