export default function admin({ next, router, user }) {
    if (!user || user.type !== 1) {
        return router.push('/');
    }

    return next();
}
