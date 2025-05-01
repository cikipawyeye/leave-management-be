import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { User } from '@/types';
import { useForm } from '@inertiajs/react';
import { Eye, EyeOff, Key } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';

type ResetPasswordForm = { password: string | undefined; password_confirmation: string | undefined };

export default function ResetUserPassword({ user }: Readonly<{ user: User }>) {
    const [open, setOpen] = useState(false);
    const [passwordOpen, setPasswordOpen] = useState(false);
    const [confirmPasswordOpen, setConfirmPasswordOpen] = useState(false);

    const { data, setData, put, errors, processing, clearErrors } = useForm<Required<ResetPasswordForm>>({
        password: undefined,
        password_confirmation: undefined,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        put(route('users.reset-password', { user: user.id }), {
            preserveScroll: true,
            onSuccess: () => setOpen(false),
        });
    };

    useEffect(() => {
        if (open) {
            clearErrors();
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open]);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant={'destructive'} size="sm">
                    <Key /> Reset Password
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[600px]">
                <form onSubmit={submit}>
                    <DialogHeader>
                        <DialogTitle>Reset User Password</DialogTitle>
                        <DialogDescription>Click save when you're done.</DialogDescription>
                    </DialogHeader>
                    <div className="mb-4 grid gap-4 py-4">
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="name" className="text-right">
                                Password
                            </Label>

                            <div className="col-span-3">
                                <div className="flex w-full max-w-sm items-center space-x-2">
                                    <Input
                                        type={passwordOpen ? 'text' : 'password'}
                                        onChange={(el) =>
                                            setData({
                                                password: el.target.value,
                                                password_confirmation: data.password_confirmation,
                                            })
                                        }
                                    />
                                    <Button variant={'ghost'} onClick={() => setPasswordOpen(!passwordOpen)} type="button">
                                        {!passwordOpen ? <EyeOff /> : <Eye />}
                                    </Button>
                                </div>

                                <InputError className="mt-2" message={errors.password} />
                            </div>
                        </div>
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="name" className="text-right">
                                Confirm Password
                            </Label>

                            <div className="col-span-3">
                                <div className="flex w-full max-w-sm items-center space-x-2">
                                    <Input
                                        type={confirmPasswordOpen ? 'text' : 'password'}
                                        onChange={(el) =>
                                            setData({
                                                password: data.password,
                                                password_confirmation: el.target.value,
                                            })
                                        }
                                    />
                                    <Button variant={'ghost'} onClick={() => setConfirmPasswordOpen(!confirmPasswordOpen)} type="button">
                                        {!confirmPasswordOpen ? <EyeOff /> : <Eye />}
                                    </Button>
                                </div>

                                <InputError className="mt-2" message={errors.password_confirmation} />
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button disabled={processing} type="submit">
                            Save changes
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
