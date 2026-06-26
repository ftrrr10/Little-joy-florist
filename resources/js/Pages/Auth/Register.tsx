import React, { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import Input from '@/Components/common/Input';
import Button from '@/Components/common/Button';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Daftar Akun Baru" />

            {/* Header Text */}
            <div className="text-center mb-8">
                <h2 className="font-serif text-2xl font-bold text-primary tracking-wide">
                    Daftar Akun Baru
                </h2>
                <p className="text-xs text-brandText-muted mt-2 leading-relaxed">
                    Buat akun Little Joy Anda untuk mulai memilih bunga segar premium dan melacak status pesanan dengan mudah.
                </p>
            </div>

            {/* Registration Form */}
            <form onSubmit={submit} className="space-y-5">
                {/* Name Field */}
                <Input
                    label="Nama Lengkap"
                    id="name"
                    type="text"
                    name="name"
                    value={data.name}
                    placeholder="Nama lengkap Anda"
                    error={errors.name}
                    autoComplete="name"
                    autoFocus
                    required
                    onChange={(e) => setData('name', e.target.value)}
                />

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
                    required
                    onChange={(e) => setData('email', e.target.value)}
                />

                {/* WhatsApp / Phone Field */}
                <Input
                    label="Nomor WhatsApp / Telepon"
                    id="phone"
                    type="tel"
                    name="phone"
                    value={data.phone}
                    placeholder="Contoh: 081234567890"
                    error={errors.phone}
                    autoComplete="tel"
                    required
                    helperText="Digunakan untuk konfirmasi pesanan via WhatsApp."
                    onChange={(e) => setData('phone', e.target.value)}
                />

                {/* Password Field */}
                <Input
                    label="Kata Sandi"
                    id="password"
                    type="password"
                    name="password"
                    value={data.password}
                    placeholder="Minimal 8 karakter"
                    error={errors.password}
                    autoComplete="new-password"
                    required
                    onChange={(e) => setData('password', e.target.value)}
                />

                {/* Confirm Password Field */}
                <Input
                    label="Konfirmasi Kata Sandi"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    value={data.password_confirmation}
                    placeholder="Ulangi kata sandi Anda"
                    error={errors.password_confirmation}
                    autoComplete="new-password"
                    required
                    onChange={(e) => setData('password_confirmation', e.target.value)}
                />

                {/* Action Button */}
                <div className="pt-2">
                    <Button
                        type="submit"
                        variant="primary"
                        className="w-full justify-center"
                        isLoading={processing}
                    >
                        Daftar Akun Baru
                    </Button>
                </div>
            </form>

            {/* Login Shortcut Footer */}
            <div className="mt-8 pt-6 border-t border-brandSurface-low text-center">
                <p className="text-xs text-brandText-muted">
                    Sudah memiliki akun?{' '}
                    <Link
                        href={route('login')}
                        className="font-bold text-primary hover:text-primary-dark hover:underline transition-all"
                    >
                        Masuk Sekarang
                    </Link>
                </p>
            </div>
        </GuestLayout>
    );
}
