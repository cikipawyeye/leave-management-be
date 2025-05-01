import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { User } from '@/types';
import { useForm } from '@inertiajs/react';
import { Check } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';

export default function Verify({ user }: Readonly<{ user: User }>) {
    const [open, setOpen] = useState(false);

    const { setData, put, processing } = useForm<Required<{ role: string | undefined }>>({
        role: user.role,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        put(route('ordinary-users.verify', { ordinary_user: user.id }), {
            preserveScroll: true,
            onSuccess: () => setOpen(false),
        });
    };

    useEffect(() => {
        if (open) {
            setData({ role: user.role });
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open]);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant={'default'} size="sm">
                    <Check /> Verify User
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
                <form onSubmit={submit}>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>This action cannot be undone. Please confirm that you want to verify this user.</DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button disabled={processing} type="submit">
                            Verify this user
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
