import mammoth from 'mammoth';

const result = await mammoth.extractRawText({ path: 'data/TSD-45bbdf.docx' });
console.log(result.value);
