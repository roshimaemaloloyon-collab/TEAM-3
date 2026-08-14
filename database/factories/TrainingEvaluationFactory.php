<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingEvaluationFactory extends Factory
{
    protected $model = \App\Models\TrainingEvaluation::class;

    public function definition(): array
    {
        $englishFeedback = [
            'Demonstrated excellent defensive driving and complete adherence to speed limits.',
            'Showed high proficiency in GPS navigation and route optimization during peak hours.',
            'Great passenger communication and exceptional vehicle cleanliness throughout the module.',
            'Good understanding of safety protocols, but needs minor improvement in smooth braking.',
            'Completed all practical assessments with high accuracy and attention to LTFRB guidelines.',
            'Showed great enthusiasm in learning eco-driving techniques to minimize fuel usage.',
            'Satisfactory performance in vehicle inspection, though tire pressure check was missed once.',
            'Handles passenger inquiries politely and follows all emergency response protocols.',
        ];

        $englishRecommendations = [
            'Recommended for advanced highway and night driving certification.',
            'Suggest enrolling in the customer service excellence workshop.',
            'Advise practicing smooth acceleration and gradual deceleration.',
            'Ready to take the senior driver mentor assessment program.',
            'Keep up the great work and maintain consistent daily BLOWBAGETS vehicle checks.',
            'Recommend refresher course on emergency braking and wet road handling.',
        ];

        return [
            'driver_id' => User::factory(),
            'training_id' => Training::factory(),
            'overall_rating' => fake()->numberBetween(1, 5),
            'knowledge_assessment' => fake()->numberBetween(1, 5),
            'instructor_feedback' => fake()->numberBetween(1, 5),
            'training_effectiveness' => fake()->numberBetween(1, 5),
            'driver_feedback' => fake()->randomElement($englishFeedback),
            'recommendations' => fake()->randomElement($englishRecommendations),
            'remarks' => 'Evaluated and logged under standard fleet compliance guidelines.',
            'status' => fake()->randomElement(['completed', 'completed', 'completed', 'pending']),
            'evaluated_by' => User::factory(),
        ];
    }
}
