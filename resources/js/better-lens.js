import LensPage from './pages/Lens';
import HasManyLensField from './components/HasManyLensField';
import HasManyThroughLensField from './components/HasManyThroughLensField';
import BelongsToManyLensField from './components/BelongsToManyLensField';
import MorphToManyLensField from './components/MorphToManyLensField';
import LensField from './components/LensField';

Nova.inertia('Nova.BetterLens', LensPage);

Nova.booting((app, store) => {
    app.component('detail-has-many-lens-field', HasManyLensField);
    app.component('detail-has-many-through-lens-field', HasManyThroughLensField);
    app.component('detail-belongs-to-many-lens-field', BelongsToManyLensField);
    app.component('detail-morph-to-many-lens-field', MorphToManyLensField);
    app.component('detail-lens-field', LensField);
});
