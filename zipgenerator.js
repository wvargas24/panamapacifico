const file_system = require('fs');
const path = require('path');
const archiver = require('archiver');
const dotenv = require('dotenv').config({ path: '.env' });

let full_path = path.join(__dirname, '../');

console.log('Compressing theme dir, this might take a few seconds...');
/** here starts the function for the "progreess bar" */
async function main() {
  /* using 20 to make the progress bar length 20 charactes, multiplying by 5 below to arrive to 100 */

  for (let i = 0; i <= 20; i++) {
    const dots = ".".repeat(i)
    const left = 20 - i
    const empty = " ".repeat(left)

    /* need to use  `process.stdout.write` becuase console.log print a newline character */
    /* \r clear the current line and then print the other characters making it looks like it refresh*/
    process.stdout.write(`\r[${dots}${empty}] ${i * 5}%`)
    await wait(200)
  }
}

main()

function wait(ms) {
  return new Promise(res => setTimeout(res, ms))
}
/** Here ends the function for "progress bar" */

let output = file_system.createWriteStream(`${full_path}${process.env.THEME_NAME}.zip`);
var archive = archiver('zip', {
  zlib: { level: 9 } //compression level
});

output.on('close', function () {
  console.log('\n');
  console.log(`The file is located on: ${full_path}${process.env.THEME_NAME}.zip`);
  console.log('The file size is:' + archive.pointer() + ' total bytes');
  console.log('Archiver has been finalized and the output file descriptor has closed.');
});

archive.on('error', function (err) {
  throw err;
});

archive.pipe(output);
// append files from a sub-directory, putting its contents at the root of archive
archive.glob(
  '**/*',
  {
    cwd: __dirname,
    ignore: [
      'node_modules/**',
      'resources/**',
      '.git/**',
      '.env',
      'zipgenerator.js',
    ],
  }
);


archive.finalize();
