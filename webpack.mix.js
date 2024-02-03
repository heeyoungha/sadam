const mix = require('laravel-mix');
// const NewassetsCmsDir = 'resources/';
// const NewDistDir = 'public/';
// mix.copy(NewassetsCmsDir, NewDistDir);

mix.postCss('resources/css/app.css','public/css');