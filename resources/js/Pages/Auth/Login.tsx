import React, { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import Input from '@/Components/common/Input';
import Button from '@/Components/common/Button';

export default function Login({
    status,
    canResetPassword,
}: {
    status?: string;
    canResetPassword: boolean;
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Masuk ke Akun" />

            {/* Header Text */}
            <div className="text-center mb-8">
                <h2 className="font-serif text-2xl font-bold text-primary tracking-wide">
                    Selamat Datang Kembali
                </h2>
                <p className="text-xs text-brandText-muted mt-2 leading-relaxed">
                    Silakan masuk ke akun Anda untuk mengelola pesanan atau memulai pemesanan bunga baru.
                </p>
            </div>

            {/* Status Alert Banner */}
            {status && (
                <div className="mb-6 p-3.5 bg-green-50 border border-green-200 text-success text-xs font-semibold rounded-lg">
                    {status}
                </div>
            )}

            {/* Login Form */}
            <form onSubmit={submit} className="space-y-5">
                {/* Email Field */}
                <Input
                    label="Alamat Email"
                    id="email"
                    type="email"
                    name="email"
                    value={data.email}
                    placeholder="nama@email.com"
                    error={errors.email}
                    autoComplete="username"
                    autoFocus
                    required
                    onChange={(e) => setData('email', e.target.value)}
                />

                {/* Password Field */}
                <div className="space-y-1">
                    <Input
                        label="Kata Sandi"
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        placeholder="••••••••"
                        error={errors.password}
                        autoComplete="current-password"
                        required
                        onChange={(e) => setData('password', e.target.value)}
                    />
                </div>

                {/* Remember Me & Forgot Password */}
                <div className="flex items-center justify-between pt-1">
                    <label className="inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            checked={data.remember}
                            className="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4 transition-colors"
                            onChange={(e) => setData('remember', e.target.checked)}
                        />
                        <span className="ms-2 text-xs font-semibold text-brandText-muted hover:text-brandText transition-colors">
                            Ingat Saya
                        </span>
                    </label>

                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-xs font-bold text-primary hover:text-primary-dark hover:underline transition-all"
                        >
                            Lupa Kata Sandi?
                        </Link>
                    )}
                </div>

                {/* Action Buttons */}
                <div className="pt-2">
                    <Button
                        type="submit"
                        variant="primary"
                        className="w-full justify-center"
                        isLoading={processing}
                    >
                        Masuk Sekarang
                    </Button>
                </div>
            </form>

            {/* Register Shortcut Footer */}
            <div className="mt-8 pt-6 border-t border-brandSurface-low text-center">
                <p className="text-xs text-brandText-muted">
                    Belum memiliki akun?{' '}
                    <Link
                        href={route('register')}
                        className="font-bold text-primary hover:text-primary-dark hover:underline transition-all"
                    >
                        Daftar Akun Baru
                    </Link>
                </p>
            </div>
        </GuestLayout>
    );
}
