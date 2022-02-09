
   // Replace the <textarea id="editor1"> with a CKEditor
   // instance, using default configuration.
   CKEDITOR.replace( 'editor1' , {
      // Define the toolbar groups as it is a more accessible solution.
      toolbarGroups: [
         { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
         { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
         { name: 'forms', groups: [ 'forms' ] },
         '/',
         { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
         { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
         { name: 'document', groups: [ 'document', 'doctools', 'mode' ] },
         { name: 'links', groups: [ 'links' ] },
         { name: 'insert', groups: [ 'insert' ] },
         '/',
         { name: 'styles', groups: [ 'styles' ] },
         { name: 'colors', groups: [ 'colors' ] },
         { name: 'tools', groups: [ 'tools' ] },
         { name: 'others', groups: [ 'others' ] },
         { name: 'about', groups: [ 'about' ] }
      ] ,
      removeButtons:'Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Templates,Save,NewPage,Preview,Print,Strike,Subscript,Superscript,RemoveFormat,CopyFormatting,Indent,Outdent,CreateDiv,BidiLtr,BidiRtl,Language,Flash,Smiley,PageBreak,Iframe,BGColor,ShowBlocks,About'
   });
   