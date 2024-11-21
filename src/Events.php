<?php

declare(strict_types=1);

namespace Limatus;

enum Events: string
{
    // FormDelegator events
    case PreRenderForm     = 'pre.render.form';
    case RenderForm        = 'render.form';
    case PostRenderForm    = 'post.render.form';
    // Fieldset/Collection events
    case RenderCollection  = 'render.collection';
    // FormInputDelegator events
    case PreRenderInput    = 'pre.render.input';
    case RenderInput       = 'render.input';
    case PostRenderInput   = 'post.render.input';
    // FormElementDelegator events
    case PreRenderElement  = 'pre.render.element';
    case RenderElement     = 'render.element';
    case PostRenderElement = 'post.render.element';
    // FormRowDelegator events
    case PreRenderRow      = 'pre.render.row';
    case RenderRow         = 'render.row';
    case PostRenderRow     = 'post.render.row';
    // Label Events
    case PreRenderLabel    = 'pre.render.label';
    case PostRenderLabel   = 'post.render.label';
    // trigger by ElementDelegator::setOptions
    case SetOptions = 'setOptions';
}
