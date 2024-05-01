<?php

// database/factories/UserFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'cpf_cnpj' => $this->faker->unique()->numerify('##############'), // Define o formato do CPF/CNPJ conforme necessário
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Defina uma senha padrão aqui, se necessário
            'type' => $this->faker->randomElement(['cliente', 'lojista']), // Define aleatoriamente o tipo entre 'cliente' e 'lojista'
        ];
    }
}
