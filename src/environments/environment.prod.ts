export const environment = {
  production: true,
  apiUrl: 'http://server.petitphotobox.com',
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
