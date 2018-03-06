export const environment = {
  production: true,
  apiUrl: 'http://server.petitphotobox.com',
  imagesDir: 'images/',
  thumbsDir: 'thumbs/',
  defaultLanguage: 'en',
  languages: new Array<Language>(
    { code: 'en', label: 'English' },
    { code: 'es', label: 'Spanish (Español)' }
  )
};

interface Language {
  code: string;
  label: string;
}
