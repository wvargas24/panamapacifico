const path = require('path');

module.exports = {
    entry: './assets/js/main.js',
    output: {
        filename: 'scripts.min.js',
        path: path.resolve(__dirname, 'dist/js'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
            // Regla para procesar archivos SCSS
            {
                test: /\.scss$/,
                use: [
                    'style-loader',  // Inyecta el CSS en el DOM
                    'css-loader',    // Resuelve los imports de CSS
                    'sass-loader',   // Compila SCSS a CSS
                ],
            },
            // Regla para procesar archivos CSS (como los de Slick y Sal)
            {
                test: /\.css$/,
                use: [
                    'style-loader', // Inyecta el CSS al DOM
                    'css-loader',   // Resuelve los imports de CSS
                ],
            },
        ],
    },
    target: ['web', 'es5'], // Genera código compatible con navegadores modernos y antiguos
    mode: 'production',
};
