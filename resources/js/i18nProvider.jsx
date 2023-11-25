import polyglotI18nProvider from "ra-i18n-polyglot";
import russianMessages from "./i18n/ru";

//Языки
const i18nProvider = polyglotI18nProvider(locale => {
    if (locale === 'en') {
        return import('./i18n/en').then(messages => messages.default);
    }

    if (locale === 'fr') {
        return import('./i18n/fr').then(messages => messages.default);
    }

    // Always fallback on english
    return russianMessages;
}, 'ru');

export default i18nProvider;
