import './bootstrap';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .then( editor => {
        console.log( 'Editor was initialized', editor );
    } )
    .catch( error => {
        console.error( error );
    } );
