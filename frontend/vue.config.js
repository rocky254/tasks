const { mergeSassVariables } = require('@vuetify/cli-plugin-utils')

module.exports = {
  transpileDependencies: ['vuetify'],
  pwa: {
    name: 'Solutech',
    themeColor: '#050A19',
    msTileColor: '#050A19',
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: '#050A19',
    iconPaths: {
      favicon32: "img/icons/favicon-16x16.png",
      favicon16: "img/icons/favicon-32x32.png",
      faviconSVG: "img/icons/favicon.svg",
      appleTouchIcon: "img/icons/apple-touch-icon.png",
      maskIcon: "img/icons/favicon.ico",
      msTileImage: "img/icons/favicon.ico"
    },
  },
  chainWebpack: config => {
    const modules = ['vue-modules', 'vue', 'normal-modules', 'normal']
    modules.forEach(match => {
      config.module
        .rule('sass')
        .oneOf(match)
        .use('sass-loader')
        .tap(opt => mergeSassVariables(opt, "'@/styles/variables.scss'"))
      config.module
        .rule('scss')
        .oneOf(match)
        .use('sass-loader')
        .tap(opt => mergeSassVariables(opt, "'@/styles/variables.scss';"))
    })
  },
  pluginOptions: {
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'locales',
      enableInSFC: true
    }
  }
};
