import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { usePermission } from '@/contexts/permission-context';
import { Permissions } from '@/Permission';
import { Permit } from '@/types';
import { useForm } from '@inertiajs/react';
import { Pen } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';
import { toast } from 'sonner';

type EditForm = {
    title: string | null;
    content: string | null;
};

export default function EditPermit({ permit }: Readonly<{ permit: Permit }>) {
    const [open, setOpen] = useState(false);

    const { hasPermission } = usePermission();

    const { data, setData, patch, errors, processing, clearErrors } = useForm<Required<EditForm>>({
        title: null,
        content: null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('permits.update', { permit: permit.id }), {
            preserveScroll: true,
            onSuccess: () => {
                setOpen(false);
                toast('Permit has been updated.');
            },
        });
    };

    useEffect(() => {
        if (open) {
            clearErrors();
            setData({
                title: permit.title,
                content: permit.content,
            });
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open, permit]);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                {hasPermission(Permissions.EDIT_PERMIT) && (permit.state == 'pending' || permit.state == 'revision') && (
                    <Button variant={'default'} size="sm">
                        <Pen /> Edit Permit
                    </Button>
                )}
            </DialogTrigger>
            <DialogContent className="sm:max-w-[600px]">
                <form onSubmit={submit}>
                    <DialogHeader>
                        <DialogTitle>Edit Permit</DialogTitle>
                        <DialogDescription>Click save when you're done.</DialogDescription>
                    </DialogHeader>
                    <div className="mb-4 grid gap-4 py-4">
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="title" className="text-right">
                                Title
                            </Label>

                            <div className="col-span-3">
                                <Input
                                    id="title"
                                    type="text"
                                    aria-invalid={!!errors.title}
                                    defaultValue={permit.title}
                                    onChange={(el) =>
                                        setData({
                                            title: el.target.value,
                                            content: data.content,
                                        })
                                    }
                                />

                                <InputError className="mt-2" message={errors.title} />
                            </div>
                        </div>
                        <div className="grid grid-cols-4 items-center gap-4">
                            <Label htmlFor="content" className="text-right">
                                Content
                            </Label>

                            <div className="col-span-3">
                                <Textarea
                                    id="content"
                                    aria-invalid={!!errors.content}
                                    defaultValue={permit.content}
                                    onChange={(el) =>
                                        setData({
                                            title: data.title,
                                            content: el.target.value,
                                        })
                                    }
                                />

                                <InputError className="mt-2" message={errors.content} />
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button disabled={processing} type="submit">
                            Save
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
