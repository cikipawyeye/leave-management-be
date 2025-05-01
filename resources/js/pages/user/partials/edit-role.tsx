import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { User } from '@/types';
import { useForm } from '@inertiajs/react';
import { Pencil } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';

export default function EditRole({ user }: Readonly<{ user: User }>) {
    const [open, setOpen] = useState(false);

    const { setData, patch, errors, processing } = useForm<Required<{ role: string | undefined }>>({
        role: user.role,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('users.update', { user: user.id }), {
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
                <Button variant={'secondary'} size="sm">
                    <Pencil /> Change Role
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
                <form onSubmit={submit}>
                    <DialogHeader>
                        <DialogTitle>Change User Role</DialogTitle>
                        <DialogDescription>Click save when you're done.</DialogDescription>
                    </DialogHeader>
                    <div className="grid gap-4 py-4">
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="name" className="text-right">
                                Role
                            </Label>

                            <div className="col-span-3">
                                <Select onValueChange={(value) => setData({ role: value })} defaultValue={user.role}>
                                    <SelectTrigger className="h-9">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="user"> Ordinary User </SelectItem>
                                        <SelectItem value="verificator"> Verificator </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div className="text-center">
                            <InputError className="mt-2" message={errors.role} />
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
