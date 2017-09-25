import Upyun from '../pages/Upyun';

export default function (injection) {
    injection.useExtensionRoute([
        {
            beforeEnter: injection.middleware.requireAuth,
            component: Upyun,
            path: 'cloud/upyun',
        },
    ]);
}
