/*'use strict';

const boton_foto = document.querySelector('#add-new-photo');
const imagen = document.querySelector('#add-photo-container');

let widget_cloudinary = cloudinary.createUploadWidget({
    cloudName: 'dnfglb0hc',
    uploadPreset: 'invjouetland'
}, (err, result) => {
    if (!err && result && result.event === 'success') {
        console.log('Imagen subida con exito', result.info);
        imagen.src= result.info.secure_url;
    }
});

boton_foto.addEventListener('click', () => {
    widget_cloudinary.open();


 
}, false);*/

'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const boton_foto = document.querySelector('#add-photo');
    const imagen = document.querySelector('#producto_foto');

    let widget_cloudinary = cloudinary.createUploadWidget({
        cloudName: 'dnfglb0hc',
        uploadPreset: 'invjouetland'
    }, (err, result) => {
        if (!err && result && result.event === 'success') {
           
            imagen.innerHTML = `<img src="${result.info.secure_url}" alt="Imagen subida">`;
        }
    });

    boton_foto.addEventListener('click', () => {
        widget_cloudinary.open();
    }, false);
});
