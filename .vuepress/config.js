const project = require('./config-project')

project.theme = 'default-prefers-color-scheme';
project.head.push(
    ['meta', {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1.0'
    }]
);
if (!project.themeConfig) {
    project.themeConfig = {};
}
if (!project.themeConfig.nav) {
    project.themeConfig.nav = [];
}
if (!project.plugins) {
    project.plugins = [];
}
project.plugins.push(
    ['@vuepress/medium-zoom', true],
    ['seo', project.themeConfig.seo || {}]
);
if (project.themeConfig.nav_before) {
    project.themeConfig.nav.unshift(
        ...project.themeConfig.nav_before
    );
}
project.themeConfig.nav.push(
    ...require('./nav/en')
);
if (project.themeConfig.nav_after) {
    project.themeConfig.nav.push(
        ...project.themeConfig.nav_after
    );
}
if (!project.themeConfig.sidebar) {
    project.themeConfig.sidebar = [];
}
project.themeConfig.sidebar = require('./sidebar/en');
module.exports = project
