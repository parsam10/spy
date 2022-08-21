<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Words;

#[Route(path: '/{_locale}/room', requirements: ['_locale' => 'en|fa'], defaults: ['_locale' => 'en'])]
class RoomController extends AbstractController
{
    #[Route('/room/{id}', name: 'app_room')]
    public function index(Room $room, EntityManagerInterface $em): Response
    {
//        if ($room->getTargetWord() == null or $room->getTargetWord() === "") {
        $places = Words::allPlaces;
        $targetPlace = $places[array_rand($places)];
        $room->setTargetWord($targetPlace);

        $allUsers = $em->getRepository(User::class)->findBy(
            ['room_id' => $room->getId()]
        );
        /** @var User $spy */
        $spy = $allUsers[array_rand($allUsers)];
        $room->setSpyMemberId($spy->getId());

        $em->flush();
//        }

        return $this->render('room/index.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/new/{id}', name: 'app_room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ?Room $room, EntityManagerInterface $em): Response
    {
        /** @var User $currUser */
        $currUser = $this->getUser();

        if ($room == null || $request->get('id') == -1) { // It's new Room
            $room = new Room();
            $room->setName(date('d-m-y h:i:s'));
            $room->setMemberCapacity(10);
            $room->setInvitationCode(rand(100000, 200000));

            $em->persist($room);
            $em->flush();

            $currUser->setIsLobbyCreator(true);
        }

        $currUser->setRoomId($room);
        $em->flush();

        $roomMembers = $em->getRepository(User::class)->findBy(
            ['room_id' => $room->getId()]
        );

        return $this->renderForm('room/new.html.twig', [
            'room' => $room,
            'members' => $roomMembers,
            'user_is_lobby_creator' => $currUser->isIsLobbyCreator()
        ]);
    }

    #[Route('/join', name: 'join_room')]
    public function join(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('code', TextType::class, ['required' => true])
            ->add('join', SubmitType::class, [
                'label' => 'JOIN',
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $enteredCode = $form->get('code')->getData();

            /** @var Room $detectedRoom */
            $detectedRoom = $em->getRepository(Room::class)->findOneBy([
                'invitation_code' => $enteredCode
            ]);
            if ($detectedRoom === null) {
                return new Response("Wrong code!");
            }

            return $this->redirectToRoute('app_room_new', ['id' => $detectedRoom->getId()]);
        }

        $allRooms = $em->getRepository(Room::class)->findBy(
            [],
            ['createdAt' => 'ASC']
        );

        $userRole = $this->getUser()->getRoles()[0];

        return $this->renderForm('room/join.html.twig', [
            'form' => $form,
            'rooms' => $allRooms,
            'user_role' => $userRole
        ]);
    }

    #[Route('/{id}/remove', name: 'room_remove')]
    public function remove(Room $room, EntityManagerInterface $em): Response
    {
        $roomMembers = $em->getRepository(User::class)->findBy([
            'room_id' => $room->getId()
        ]);

        /** @var User $member */
        foreach ($roomMembers as $member) {
            $member->setRoomId(null);
        }
        $em->remove($room);

        $em->flush();

        return $this->redirectToRoute('app_new_game');
    }
}
