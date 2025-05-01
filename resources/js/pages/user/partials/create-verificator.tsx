import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { toTitleCase } from '@/lib/helpers';
import { useForm } from '@inertiajs/react';
import { Eye, EyeOff, Plus } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';
import { toast } from 'sonner';

type CreateUserForm = {
    name: string | null;
    email: string | null;
    password: string | null;
    password_confirmation: string | null;
    role: 'verificator' | 'user' | undefined;
    should_verify_email: boolean;
};

export default function CreateVerificator() {
    const [open, setOpen] = useState(false);
    const [passwordOpen, setPasswordOpen] = useState(false);
    const [confirmPasswordOpen, setConfirmPasswordOpen] = useState(false);

    const { data, setData, post, errors, processing, clearErrors } = useForm<Required<CreateUserForm>>({
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
        role: 'verificator',
        should_verify_email: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('users.store'), {
            preserveScroll: true,
            onSuccess: () => {
                setOpen(false);
                toast('User has been created.');
            },
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
                <Button variant={'default'} size="sm">
                    <Plus /> Add Verificator
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[600px]">
                <form onSubmit={submit}>
                    <DialogHeader>
                        <DialogTitle>Add Verificator</DialogTitle>
                        <DialogDescription>Click save when you're done.</DialogDescription>
                    </DialogHeader>
                    <div className="mb-4 grid gap-4 py-4">
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="role" className="text-right">
                                Role
                            </Label>

                            <div className="col-span-3">
                                <Input id="role" type="text" value={data.role ? toTitleCase(data.role) : ''} disabled />
                            </div>
                        </div>
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="name" className="text-right">
                                Full Name
                            </Label>

                            <div className="col-span-3">
                                <Input
                                    id="name"
                                    type="text"
                                    aria-invalid={!!errors.name}
                                    onChange={(el) =>
                                        setData({
                                            name: el.target.value,
                                            email: data.email,
                                            role: data.role,
                                            password: data.password,
                                            password_confirmation: data.password_confirmation,
                                            should_verify_email: data.should_verify_email,
                                        })
                                    }
                                />

                                <InputError className="mt-2" message={errors.name} />
                            </div>
                        </div>
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="email" className="text-right">
                                Email
                            </Label>

                            <div className="col-span-3">
                                <Input
                                    id="email"
                                    type="email"
                                    aria-invalid={!!errors.email}
                                    onChange={(el) =>
                                        setData({
                                            name: data.name,
                                            email: el.target.value,
                                            role: data.role,
                                            password: data.password,
                                            password_confirmation: data.password_confirmation,
                                            should_verify_email: data.should_verify_email,
                                        })
                                    }
                                />

                                <InputError className="mt-2" message={errors.email} />
                            </div>
                        </div>
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="password" className="text-right">
                                Password
                            </Label>

                            <div className="col-span-3">
                                <div className="flex w-full items-center gap-2">
                                    <Input
                                        id="password"
                                        type={passwordOpen ? 'text' : 'password'}
                                        aria-invalid={!!errors.password}
                                        onChange={(el) =>
                                            setData({
                                                name: data.name,
                                                email: data.email,
                                                role: data.role,
                                                password: el.target.value,
                                                password_confirmation: data.password_confirmation,
                                                should_verify_email: data.should_verify_email,
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
                            <Label htmlFor="password_confirmation" className="text-right">
                                Confirm Password
                            </Label>

                            <div className="col-span-3">
                                <div className="flex w-full items-center gap-2">
                                    <Input
                                        id="password_confirmation"
                                        type={confirmPasswordOpen ? 'text' : 'password'}
                                        aria-invalid={!!errors.password_confirmation}
                                        onChange={(el) =>
                                            setData({
                                                name: data.name,
                                                email: data.email,
                                                role: data.role,
                                                password: data.password,
                                                password_confirmation: el.target.value,
                                                should_verify_email: data.should_verify_email,
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
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="should_verify_email" className="text-right">
                                Should verify email
                            </Label>

                            <div className="col-span-3">
                                <div className="flex items-center space-x-2">
                                    <Switch
                                        defaultChecked={data.should_verify_email}
                                        onCheckedChange={(el) =>
                                            setData({
                                                name: data.name,
                                                email: data.email,
                                                role: data.role,
                                                password: data.password,
                                                password_confirmation: data.password_confirmation,
                                                should_verify_email: el,
                                            })
                                        }
                                        id="should_verify_email"
                                    />
                                    <Label htmlFor="should_verify_email">{data.should_verify_email ? 'Yes' : 'No'}</Label>
                                </div>
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
