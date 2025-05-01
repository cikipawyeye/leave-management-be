import { Head } from '@inertiajs/react';

import TextLink from '@/components/text-link';
import AuthLayout from '@/layouts/auth-layout';

export default function WaitingForVerification() {
    return (
        <AuthLayout
            title="Waiting for account verification"
            description="Please wait for your account to be verified. You can login to your account once it is verified."
        >
            <Head title="Account verification" />

            <div className="space-y-6 text-center">
                <TextLink href={route('logout')} method="post" className="mx-auto block text-sm">
                    Log out
                </TextLink>
            </div>
        </AuthLayout>
    );
}
