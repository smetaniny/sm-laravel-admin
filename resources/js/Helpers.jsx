/**
 * Получаем ширину экрана
 * @returns {number}
 */
const getScreenWidth = () => {
    return Math.max(document.body.offsetWidth, document.documentElement.offsetWidth);
};

/**
 * Определяем является ли экран настольным компьютером
 * @returns {boolean}
 */
const isDesktop = () => {
    return getScreenWidth() > 1441;
};

/**
 * Определяем является ли экран крупным настольным компьютером
 * @returns {boolean}
 */
const isLargeDesktop = () => {
    return getScreenWidth() <= 1400;
};

/**
 * Определяем является ли экран средним настольным компьютером
 * @returns {boolean}
 */
const isMediumDesktop = () => {
    return getScreenWidth() <= 1280;
};

/**
 * Определяем является ли экран маленьким настольным компьютером
 * @returns {boolean}
 */
const isSmallDesktop = () => {
    return getScreenWidth() <= 1199;
};

/**
 * Определяем является ли экран планшетом
 * @returns {boolean}
 */
const isTablet = () => {
    return getScreenWidth() <= 1024;
};

/**
 * Определяем является ли экран мобильным устройством
 * @returns {boolean}
 */
const isMobile = () => {
    return getScreenWidth() <= 767;
};

/**
 * Определяем является ли экран маленьким мобильным устройством (телефоном)
 * @returns {boolean}
 */
const isSmallMobile = () => {
    return getScreenWidth() < 576;
};


export {
    isDesktop,
    isLargeDesktop,
    isMediumDesktop,
    isSmallDesktop,
    isTablet,
    isMobile,
    isSmallMobile
};
