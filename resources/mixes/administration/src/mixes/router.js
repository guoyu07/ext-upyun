import Upyun from '../pages/Upyun.vue';

export default function (injection) {
    injection.useExtensionRoute([
        {
            beforeEnter: injection.middleware.requireAuth,
            component: Upyun,
            path: 'cloud/upyun',
        },
    ]);
}
