import toast from 'react-hot-toast';

export const showToast = {
    success: (message: string) => {
        toast.success(message, {
            icon: '✅',
        });
    },
    error: (message: string) => {
        toast.error(message, {
            icon: '❌',
        });
    },
    loading: (message: string) => {
        return toast.loading(message);
    },
    dismiss: (toastId: string) => {
        toast.dismiss(toastId);
    }
};