<?php

namespace AmaizingCompany\CertifactionClient\Tests;

use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;

class ApiTestCase extends TestCase
{
    public function getPdfFileContents(): string
    {
        return file_get_contents('./tests/data/Test_PDF.pdf');
    }

    public function getEnvelopeItemData(): array
    {
        return [
            'legal_weight' => 'standard',
            'jurisdiction' => '',
            'status' => fake()->randomElement(DocumentStatus::class),
            'signed_at' => fake()->dateTime(),
            'file_url' => fake()->url(),
            'file_id' => 'file_id',
            'comments' => [],
        ];
    }

    public function getOrganizationResponseData(): array
    {
        return [
            [
                'id' => 'test_org_id',
                'name' => 'test org name',
                'name_verified' => true,
                'organization_users' => [
                    [
                        'inviter' => [
                            'external_id' => '',
                            'name_verified' => false,
                            'citizenship' => '',
                            'idnow_verified' => null,
                            'organizations' => null,
                            'subscription_status' => null,
                            'sponsor' => '',
                            'legacy_credits' => false,
                            'created_at' => '0001-01-01T00:00:00Z',
                        ],
                        'user' => [
                            'id' => 123456,
                            'uid' => 'test_uid',
                            'external_id' => '',
                            'email' => 'test@example.com',
                            'name' => 'Robert Smith',
                            'name_verified' => true,
                            'citizenship' => '',
                            'idnow_verified' => null,
                            'organizations' => null,
                            'subscription_status' => null,
                            'sponsor' => '',
                            'legacy_credits' => true,
                            'created_at' => '0001-01-01T00:00:00Z',
                        ],
                        'role' => 'admin',
                        'status' => 'joined',
                        'invite_email' => null,
                        'organization' => false,
                        'is_admin' => true,
                        'roles' => [
                            [
                                'id' => 'test_admin_role_id',
                                'name' => 'Admin',
                                'default' => false,
                                'admin' => true,
                            ],
                        ],
                        'teamspaces' => [
                            [
                                'id' => 'test_teamspace_id',
                                'name' => 'Testspace',
                            ],
                        ],
                    ],
                    [
                        'inviter' => [
                            'id' => 123456,
                            'uid' => 'test_uid',
                            'external_id' => '',
                            'email' => 'test@example.com',
                            'name' => 'Robert Smith',
                            'name_verified' => false,
                            'citizenship' => '',
                            'idnow_verified' => null,
                            'organizations' => null,
                            'subscription_status' => null,
                            'sponsor' => '',
                            'legacy_credits' => false,
                            'created_at' => '0001-01-01T00:00:00Z',
                        ],
                        'user' => [
                            'id' => 123457,
                            'uid' => 'test_uid_2',
                            'external_id' => '',
                            'email' => 'test2@example.com',
                            'name' => 'Max Smithy',
                            'name_verified' => false,
                            'citizenship' => '',
                            'idnow_verified' => null,
                            'organizations' => null,
                            'subscription_status' => null,
                            'sponsor' => '',
                            'legacy_credits' => false,
                            'created_at' => '0001-01-01T00:00:00Z',
                        ],
                        'role' => 'user',
                        'status' => 'joined',
                        'invite_email' => 'test2@example.com',
                        'organization' => false,
                        'is_admin' => false,
                        'roles' => [
                            [
                                'id' => 'test_user_role_id',
                                'name' => 'User',
                                'default' => true,
                                'admin' => false,
                            ],
                        ],
                    ],
                ],
                'quota' => 0,
                'credit_type' => 'flat_rate',
                'subscription_type' => 'flat_rate',
                'subscription_status' => null,
                'legacy_credits' => true,
                'roles' => [
                    [
                        'id' => 'test_admin_role_id',
                        'name' => 'Admin',
                        'default' => false,
                        'admin' => true,
                    ],
                    [
                        'id' => 'test_user_role_id',
                        'name' => 'User',
                        'default' => true,
                        'admin' => false,
                    ],
                ],
            ],
        ];
    }
}
